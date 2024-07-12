import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';
import axios from "axios";
import {useEffect} from "react";

export default function Dashboard({ auth, users }: PageProps) {

    useEffect(() => {
        const channel = window.Echo.private('my.private.' + auth.user.id)
            .listen('.file.ready', (e: any) => {
                alert('Download is ready!!')
            console.log(e, 'file.ready')
        });

        return () => {
            channel.unsubscribe();
        };
    },[])

    const handleExport = ():void  => {
        axios.get(route('export'))
            .then((e) => {
                alert('Download will be ready soon!!')
            })
            .catch((e) => console.log(e));
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard"/>

            <div className="flex justify-center mt-2">
                <button
                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    onClick={() => handleExport()}
                     >EXPORTAR TODOS OS REGISTROS</button>

            </div>

            <div className="m-8">
                   <ul className='flex text-white justify-center'>
                        {
                            users?.links.map((link: any) => {
                                return (
                                    link.url  ?
                                        <li key={link.label} className={ "p-4 " + ( link.active ? 'text-red-500' : '') } >
                                            <a href={link.url} >
                                                {
                                                    link.label
                                                }
                                            </a>
                                        </li>
                                        :
                                        <li key={link.label} className="p-4">
                                            <span>{link.label}</span>
                                        </li>
                                )




                            })
                        }


                    </ul>



                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="row"
                            className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            #ID
                        </th>
                        <th scope="row"
                            className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                            Name
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    {
                        users?.data.map((i: any) => {
                            return (
                                <tr key={i.email} className="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">

                                    <td className="px-6 py-4">{i.id}</td>
                                    <td className="px-6 py-4">{i.name}</td>
                                </tr>
                            )

                        })
                    }
                    </tbody>
                </table>
            </div>



        </AuthenticatedLayout>
    );
}

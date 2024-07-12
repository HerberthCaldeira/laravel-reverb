import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from '@inertiajs/react';
import { PageProps } from '@/types';

export default function Files({auth, files}: PageProps) {


    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>}
        >
            <Head title="Files"/>

            <div className="text-white">
                {
                    files?.map((i: any) => {
                        return (
                            <div key={i.id}>

                                <a  href={'/download/' + i.id } >  File:: {i.created_at}</a>
                            </div>

                        )
                    })

                }
            </div>


        </AuthenticatedLayout>

);

}

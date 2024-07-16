import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head} from '@inertiajs/react';
import { PageProps } from '@/types';

interface IFile {
    id: number;
    created_at: string;
}

interface IFilesProps extends PageProps {
    files: IFile[];
}

export default function Files({auth, files}: IFilesProps) {

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

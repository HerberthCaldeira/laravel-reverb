import {User} from "@/types";

interface IPaginationLinks {
    active: boolean;
    label: string;
    url: string | null;
}

export interface PaginatedResponse<RESOURCE> {
    current_page: number;
    data: RESOURCE[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: IPaginationLinks[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

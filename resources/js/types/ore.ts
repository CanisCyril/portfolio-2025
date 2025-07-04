export interface Ore {
    id: number;
    name: string;
    display_name: string;
    value: number;
    level_requirement: number;
    created_at: string | null;
    updated_at: string | null;
}
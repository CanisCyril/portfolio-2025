import type { Ore } from './ore';

export interface Inventory {
  id: number;
    user_id: number;
    ore_id: number;
    amount: number;
    created_at: string | null;
    updated_at: string | null;
    ore: Ore;
}
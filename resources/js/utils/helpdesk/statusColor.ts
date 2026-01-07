export function statusColor(input: string = ''): string {
  const map = {
    open: 'min-h-6 min-w-[80px] md:min-w-18 badge-success text-black',
    assigned: 'min-h-6 min-w-[80px] md:min-w-18 badge-info',
    pending: 'min-h-6 min-w-[80px] md:min-w-18 badge-warning',
    overdue: 'min-h-6 min-w-[80px] md:min-w-18 badge-error',
    closed: 'min-h-6 min-w-[80px] md:min-w-18 badge-neutral dark:badge-ghost',
  } as const;

  const key = input.trim().toLowerCase() as keyof typeof map;
  console.log('STATUS COLOR INPUT', input, key);
  return map[key] ?? 'min-h-6 min-w-[80px] md:min-w-18 badge-neutral';
}

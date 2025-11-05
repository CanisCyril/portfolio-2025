export function statusColor(input: string = ''): string {
  const map = {
    open: 'badge-success text-black',
    assigned: 'badge-info',
    pending: 'badge-warning',
    overdue: 'badge-error',
    closed: 'badge-neutral dark:badge-ghost',
  } as const;

  const key = input.trim().toLowerCase() as keyof typeof map;
  return map[key] ?? 'badge-neutral';
}

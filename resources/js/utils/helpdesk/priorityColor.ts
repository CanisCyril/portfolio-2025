export function priorityColor(input: string = ''): string {
  const map = {
    low: 'badge-success text-black',
    medium: 'badge-warning',
    high: 'badge-secondary',
    critical: 'badge-error',
  } as const;

  const key = input.trim().toLowerCase() as keyof typeof map;
  return map[key] ?? 'badge-neutral';
}

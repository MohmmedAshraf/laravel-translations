export const toItems = (record: Record<string, string>) =>
    Object.entries(record).map(([value, label]) => {
        return { label, value }
    })

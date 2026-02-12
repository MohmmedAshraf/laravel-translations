export function getCsrfToken(): string {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : '';
}

export class ApiError extends Error {
    constructor(
        message: string,
        public status: number,
        public data: Record<string, unknown> = {},
    ) {
        super(message);
    }
}

function handleError(res: Response): Promise<never> {
    return res.json().then((data) => {
        throw new ApiError(
            data.message || `Request failed (${res.status})`,
            res.status,
            data,
        );
    });
}

export function apiGet<T = unknown>(url: string): Promise<T> {
    return fetch(url, {
        headers: {
            Accept: 'application/json',
        },
    }).then((res) => {
        if (!res.ok) {
            return handleError(res);
        }
        return res.json();
    });
}

export function apiPost<T = unknown>(
    url: string,
    body: Record<string, unknown>,
    options?: { signal?: AbortSignal },
): Promise<T> {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-XSRF-TOKEN': getCsrfToken(),
        },
        body: JSON.stringify(body),
        signal: options?.signal,
    }).then((res) => {
        if (!res.ok) {
            return handleError(res);
        }
        return res.json();
    });
}

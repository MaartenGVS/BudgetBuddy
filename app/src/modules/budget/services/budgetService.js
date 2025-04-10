const BASE_URL = import.meta.env.VITE_APP_API_URL;

export default class BudgetService {
    async get(lang, month, year) {
        const response = await fetch(`${BASE_URL}/budget?month=${month}&year=${year}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
        });
        return response.json();
    }
}
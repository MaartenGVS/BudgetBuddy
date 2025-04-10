const BASE_URL = import.meta.env.VITE_APP_API_URL;

export default class TransactionService {

    async getAll(lang, month, year, type, search, perPage, page) {
        const response = await fetch(`${BASE_URL}/transactions?month=${month}&year=${year}&type=${type}&search=${search}&perPage=${perPage}&page=${page}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
        });
        return await response.json();
    }


    async create(lang, transaction) {
        const response = await fetch(`${BASE_URL}/transactions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
            body: JSON.stringify({
                "month": transaction.month,
                "year": transaction.year,
                "category_id": transaction.category_id,
                "description": transaction.description,
                "amount": transaction.amount,
            })
        });
        return await response.json();
    }



    async update(lang, transaction) {
        const response = await fetch(`${BASE_URL}/transactions/${transaction.transaction_number}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
            body: JSON.stringify({
                "month": transaction.month,
                "year": transaction.year,
                "category_id": transaction.category_id,
                "description": transaction.description,
                "amount": transaction.amount,
            })
        });
        return await response.json();
    }

    async get(lang, transactionId) {
        const response = await fetch(`${BASE_URL}/transactions/${transactionId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
        });
        return await response.json();
    }

    delete(lang, transactionId) {
        return fetch(`${BASE_URL}/transactions/${transactionId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
        });
    }
}
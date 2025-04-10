const BASE_URL = import.meta.env.VITE_APP_API_URL


export default class CategoryService {

    async getAll(lang, type, search, sortBy, sortDirection, perPage, page) {
        const response = await fetch(`${BASE_URL}/categories?type=${type}&search=${search}&sortBy=${sortBy}&sortDirection=${sortDirection}&perPage=${perPage}&page=${page}`, {
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

    async getAllWithElevatedPermissions(lang, type, search, sortBy, sortDirection, perPage, currentPage) {
        const response = await fetch(`${BASE_URL}/admin/categories?type=${type}&search=${search}&sortBy=${sortBy}&sortDirection=${sortDirection}&perPage=${perPage}&page=${currentPage}`, {
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


    async get(categoryId) {
        const response = await fetch(`${BASE_URL}/admin/categories/${categoryId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': 'en'
            },
            credentials: 'include',
        });
        return await response.json();
    }

    update(lang, category) {
        return fetch(`${BASE_URL}/admin/categories/${category.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
            body: JSON.stringify(category)
        });
    }

    create(lang, category) {
        return fetch(`${BASE_URL}/admin/categories`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': lang
            },
            credentials: 'include',
            body: JSON.stringify(category)
        });
    }

    delete(lang, categoryId) {
        return fetch(`${BASE_URL}/admin/categories/${categoryId}`, {
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
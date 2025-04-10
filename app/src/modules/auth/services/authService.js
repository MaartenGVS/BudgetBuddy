const BASE_URL = import.meta.env.VITE_APP_API_URL;

export default class AuthService {

    name
    email
    password
    confirmPassword
    response
    error


    constructor(name, email, password, confirmPassword, response, error) {
        this.name = name;
        this.email = email;
        this.password = password;
        this.confirmPassword = confirmPassword;
        this.response = response;
        this.error = error;
    }

    setName(name) {
        this.name = name;
    }

    setEmail(email) {
        this.email = email;
    }

    setPassword(password) {
        this.password = password;
    }

    setConfirmPassword(confirmPassword) {
        this.confirmPassword = confirmPassword;
    }

    getError() {
        return this.error;
    }

    getUser() {
        return this.response.user;
    }

    async login(locale) {
        const response = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'credentials': 'include',
                'Accept-Language': locale,
            },
            credentials: 'include',
            body: JSON.stringify({
                email: this.email,
                password: this.password,
            }),

        });
        const data = await response.json();
        if (response.ok) {
            this.error = null;
            this.response = data;
        } else {
            this.error = data;
            this.response = null;
        }
    }

    async register(locale) {
        const response = await fetch(`${BASE_URL}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Accept-Language': locale,
            },
            body: JSON.stringify({
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.confirmPassword,
            }),
        });
        const data = await response.json();
        if (response.ok) {
            this.error = null;
            this.response = data;
        } else {
            this.error = data;
            this.response = null;
        }
    }

    async getUserProfile(locale) {
        const response = await fetch(`${BASE_URL}/profile`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'credentials': 'include',
                    'Accept-Language': locale,
                },
                credentials: 'include',
            }
        );
        const data = await response.json();
        if (response.ok) {
            this.error = null;
            this.response = data;
        } else {
            this.error = data;
            this.response = null;
        }
    }

    async logout(locale) {
        const response = await fetch(`${BASE_URL}/logout`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Accept-Language': locale,
                'credentials': 'include'
            },
            credentials: 'include'
        });
        const data = await response.json();
        if (response.ok) {
            this.error = null;
            this.response = data;
        } else {
            this.error = data;
            this.response = null;
        }
    }

    userIsAdmin() {
        return this.response?.data?.is_admin === 1;
    }
}
import axios from 'axios';
import type {
    AuthResponse,
    AccountsResponse,
    CreateAccountResponse,
    DepositResponse,
    BalanceResponse,
} from '@/types';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export const auth = {
    register: async (data: { name: string; cpf: string; email: string; password: string }) => {
        const response = await api.post<AuthResponse>('/register', data);
        return response.data;
    },

    login: async (data: { email: string; password: string }) => {
        const response = await api.post<AuthResponse>('/login', data);
        return response.data;
    },

    logout: async () => {
        const response = await api.post('/logout');
        return response.data;
    },
};

export const accounts = {
    list: async () => {
        const response = await api.get<AccountsResponse>('/accounts');
        return response.data;
    },

    create: async (data: { type: 'savings' | 'checking' | 'investment' }) => {
        const response = await api.post<CreateAccountResponse>('/accounts', data);
        return response.data;
    },

    deposit: async (id: number, data: { amount: number }) => {
        const response = await api.post<DepositResponse>(`/accounts/${id}/deposit`, data);
        return response.data;
    },

    getBalance: async (id: number) => {
        const response = await api.get<BalanceResponse>(`/accounts/${id}/balance`);
        return response.data;
    },

    applyInterest: async () => {
        const response = await api.post('/accounts/apply-interest');
        return response.data;
    },
};

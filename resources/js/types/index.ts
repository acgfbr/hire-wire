export interface User {
    id: number;
    name: string;
    email: string;
    cpf: string;
    created_at: string;
    updated_at: string;
}

export interface Account {
    id: number;
    user_id: number;
    type: 'savings' | 'checking' | 'investment';
    balance: string;
    created_at: string;
    updated_at: string;
}

export interface AuthResponse {
    user: User;
    token: string;
}

export interface AccountsResponse {
    accounts: {
        savings: Account[];
        checking: Account[];
        investment: Account[];
    };
}

export interface CreateAccountResponse {
    message: string;
    account: Account;
}

export interface DepositResponse {
    message: string;
    new_balance: string;
}

export interface BalanceResponse {
    balance: string;
    type: Account['type'];
}

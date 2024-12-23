import React from 'react';
import { Navigate } from 'react-router-dom';
import Login from '@/pages/auth/Login';
import Register from '@/pages/auth/Register';
import AccountList from '@/pages/accounts/List';
import CreateAccount from '@/pages/accounts/Create';
import Deposit from '@/pages/accounts/Deposit';

const PrivateRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const token = localStorage.getItem('token');
    if (!token) {
        return <Navigate to="/login" replace />;
    }
    return <>{children}</>;
};

export const routes = [
    {
        path: '/',
        element: <Navigate to="/accounts" replace />,
    },
    {
        path: '/login',
        element: <Login />,
    },
    {
        path: '/register',
        element: <Register />,
    },
    {
        path: '/accounts',
        element: (
            <PrivateRoute>
                <AccountList />
            </PrivateRoute>
        ),
    },
    {
        path: '/accounts/create',
        element: (
            <PrivateRoute>
                <CreateAccount />
            </PrivateRoute>
        ),
    },
    {
        path: '/accounts/:id/deposit',
        element: (
            <PrivateRoute>
                <Deposit />
            </PrivateRoute>
        ),
    },
];

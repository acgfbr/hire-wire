import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { accounts, auth } from '@/services/api';
import type { Account } from '@/types';

const AccountList: React.FC = () => {
    const navigate = useNavigate();
    const { data, isLoading, refetch } = useQuery({
        queryKey: ['accounts'],
        queryFn: accounts.list,
    });

    const applyInterestMutation = useMutation({
        mutationFn: accounts.applyInterest,
        onSuccess: () => refetch(),
    });

    const logoutMutation = useMutation({
        mutationFn: auth.logout,
        onSuccess: () => {
            localStorage.removeItem('token');
            navigate('/login');
        },
    });

    const formatBalance = (balance: string) => {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(Number(balance));
    };

    const renderAccountList = (title: string, accountList: Account[] | undefined) => {
        if (!accountList) return null;

        return (
            <div className="mt-6">
                <h3 className="text-lg font-medium text-gray-900">{title}</h3>
                {accountList.length === 0 ? (
                    <p className="mt-2 text-sm text-gray-500">No accounts found.</p>
                ) : (
                    <div className="mt-3 grid gap-4">
                        {accountList.map((account) => (
                            <div
                                key={account.id}
                                className="bg-white shadow rounded-lg p-4 flex justify-between items-center"
                            >
                                <div>
                                    <p className="text-sm font-medium text-gray-900">
                                        Balance: {formatBalance(account.balance)}
                                    </p>
                                    <p className="text-sm text-gray-500">
                                        Created at: {new Date(account.created_at).toLocaleDateString()}
                                    </p>
                                </div>
                                <Link
                                    to={`/accounts/${account.id}/deposit`}
                                    className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Deposit
                                </Link>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        );
    };

    if (isLoading) {
        return (
            <div className="min-h-screen bg-gray-100 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <p className="text-base text-gray-600">Loading...</p>
                    </div>
                </div>
            </div>
        );
    }

    if (!data?.accounts) {
        return (
            <div className="min-h-screen bg-gray-100 py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <p className="text-base text-gray-600">No accounts data available.</p>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-gray-100 py-12">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center">
                    <h2 className="text-3xl font-extrabold text-gray-900">Your Accounts</h2>
                    <div className="space-x-4 flex items-center">
                        <button
                            onClick={() => logoutMutation.mutate()}
                            disabled={logoutMutation.isPending}
                            className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 shadow-sm"
                        >
                            {logoutMutation.isPending ? 'Logging out...' : 'Logout'}
                        </button>
                        <div className="h-6 w-px bg-gray-300"></div>
                        <Link
                            to="/accounts/create"
                            className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            Create Account
                        </Link>
                        <button
                            onClick={() => applyInterestMutation.mutate()}
                            disabled={applyInterestMutation.isPending}
                            className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 disabled:opacity-50"
                        >
                            {applyInterestMutation.isPending ? 'Applying...' : 'Apply Monthly Interest'}
                        </button>
                    </div>
                </div>

                {renderAccountList('Savings Accounts', data.accounts.savings)}
                {renderAccountList('Checking Accounts', data.accounts.checking)}
                {renderAccountList('Investment Accounts', data.accounts.investment)}
            </div>
        </div>
    );
};

export default AccountList;

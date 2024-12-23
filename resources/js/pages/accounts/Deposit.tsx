import React from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { useMutation } from '@tanstack/react-query';
import { accounts } from '@/services/api';

const Deposit: React.FC = () => {
    const navigate = useNavigate();
    const { id } = useParams<{ id: string }>();
    const [error, setError] = React.useState<string>('');

    const mutation = useMutation({
        mutationFn: (amount: number) => accounts.deposit(Number(id), { amount }),
        onSuccess: () => {
            navigate('/accounts');
        },
        onError: () => {
            setError('Failed to make deposit. Please try again.');
        },
    });

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        setError('');
        const formData = new FormData(e.currentTarget);
        const amount = Number(formData.get('amount'));

        if (isNaN(amount) || amount <= 0) {
            setError('Please enter a valid amount greater than 0');
            return;
        }

        mutation.mutate(amount);
    };

    return (
        <div className="min-h-screen bg-gray-100 py-12">
            <div className="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                <div className="bg-white shadow rounded-lg">
                    <div className="px-4 py-5 sm:p-6">
                        <h3 className="text-lg font-medium leading-6 text-gray-900">Make a Deposit</h3>
                        <form onSubmit={handleSubmit} className="mt-5">
                            {error && (
                                <div className="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                                    <p className="text-red-700">{error}</p>
                                </div>
                            )}

                            <div>
                                <label htmlFor="amount" className="block text-sm font-medium text-gray-700">
                                    Amount (R$)
                                </label>
                                <div className="mt-1">
                                    <input
                                        type="number"
                                        name="amount"
                                        id="amount"
                                        step="0.01"
                                        min="0.01"
                                        required
                                        className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="0.00"
                                    />
                                </div>
                            </div>

                            <div className="mt-6">
                                <button
                                    type="submit"
                                    disabled={mutation.isPending}
                                    className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                >
                                    {mutation.isPending ? 'Processing...' : 'Make Deposit'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Deposit;

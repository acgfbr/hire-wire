import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useMutation } from '@tanstack/react-query';
import { accounts } from '@/services/api';

const accountTypes = [
    { id: 'savings', name: 'Savings Account', description: 'No deposit bonus, 0.001% monthly interest' },
    { id: 'checking', name: 'Checking Account', description: 'R$0.50 deposit bonus, 0.1% monthly interest' },
    { id: 'investment', name: 'Investment Account', description: 'R$0.50 deposit bonus, 0.1% monthly interest' },
];

const CreateAccount: React.FC = () => {
    const navigate = useNavigate();
    const [error, setError] = React.useState<string>('');
    const [selectedType, setSelectedType] = React.useState<string>('');

    const mutation = useMutation({
        mutationFn: accounts.create,
        onSuccess: () => {
            navigate('/accounts');
        },
        onError: () => {
            setError('Failed to create account. Please try again.');
        },
    });

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (!selectedType) {
            setError('Please select an account type');
            return;
        }
        mutation.mutate({ type: selectedType as 'savings' | 'checking' | 'investment' });
    };

    return (
        <div className="min-h-screen bg-gray-100 py-12">
            <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="bg-white shadow rounded-lg">
                    <div className="px-4 py-5 sm:p-6">
                        <h3 className="text-lg font-medium leading-6 text-gray-900">Create New Account</h3>
                        <form onSubmit={handleSubmit} className="mt-5">
                            {error && (
                                <div className="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                                    <p className="text-red-700">{error}</p>
                                </div>
                            )}

                            <div className="space-y-4">
                                {accountTypes.map((type) => (
                                    <div
                                        key={type.id}
                                        className={`relative block rounded-lg border p-4 cursor-pointer focus:outline-none ${
                                            selectedType === type.id
                                                ? 'bg-blue-50 border-blue-200'
                                                : 'border-gray-300'
                                        }`}
                                        onClick={() => setSelectedType(type.id)}
                                    >
                                        <input
                                            type="radio"
                                            name="account-type"
                                            value={type.id}
                                            className="sr-only"
                                            checked={selectedType === type.id}
                                            onChange={() => setSelectedType(type.id)}
                                        />
                                        <div className="flex items-center">
                                            <div className="text-sm">
                                                <p className="font-medium text-gray-900">{type.name}</p>
                                                <p className="text-gray-500">{type.description}</p>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="mt-6">
                                <button
                                    type="submit"
                                    disabled={mutation.isPending || !selectedType}
                                    className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                                >
                                    {mutation.isPending ? 'Creating...' : 'Create Account'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CreateAccount;

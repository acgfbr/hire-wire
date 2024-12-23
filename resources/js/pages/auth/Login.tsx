import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useMutation } from '@tanstack/react-query';
import { auth } from '@/services/api';

const Login: React.FC = () => {
    const navigate = useNavigate();
    const [error, setError] = React.useState<string>('');

    const mutation = useMutation({
        mutationFn: auth.login,
        onSuccess: (data) => {
            localStorage.setItem('token', data.token);
            navigate('/accounts');
        },
        onError: () => {
            setError('Invalid credentials');
        },
    });

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        setError('');
        const formData = new FormData(e.currentTarget);
        mutation.mutate({
            email: formData.get('email') as string,
            password: formData.get('password') as string,
        });
    };

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div className="sm:mx-auto sm:w-full sm:max-w-md">
                <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Login to your account
                </h2>
            </div>

            <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <form className="space-y-6" onSubmit={handleSubmit}>
                        {error && (
                            <div className="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                                <p className="text-red-700">{error}</p>
                            </div>
                        )}

                        <div>
                            <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <div className="mt-1">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    required
                                    className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <div className="mt-1">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <div>
                            <button
                                type="submit"
                                disabled={mutation.isPending}
                                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                            >
                                {mutation.isPending ? 'Loading...' : 'Login'}
                            </button>
                        </div>
                    </form>

                    <div className="mt-6">
                        <Link
                            to="/register"
                            className="text-sm text-blue-600 hover:text-blue-500 block text-center"
                        >
                            Don't have an account? Register here
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Login;

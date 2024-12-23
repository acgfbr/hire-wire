import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { RouterProvider, createBrowserRouter } from 'react-router-dom';
import { routes } from './routes';
import '../css/app.css';

const queryClient = new QueryClient();
const router = createBrowserRouter(routes);

const app = document.getElementById('app');

if (app) {
    createRoot(app).render(
        <React.StrictMode>
            <QueryClientProvider client={queryClient}>
                <RouterProvider router={router} />
            </QueryClientProvider>
        </React.StrictMode>
    );
}

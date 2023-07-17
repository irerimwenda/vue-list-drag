<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-center bg-white selection:bg-red-500 selection:text-dark">
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('index')"
                class="font-semibold text-dark hover:text-dark dark:text-dark dark:hover:text-dark focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</Link>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <img src="/images/CTL.png" alt="Coalition Technologies" class="mb-24"/>

            <p class="text-xl mb-8 font-bold text-center">Laravel task management web app task</p>

            <div v-if="!$page.props.auth.user" class="text-center">
                <Link
                    :href="route('login')"
                    class="font-semibold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Log in</Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">Register</Link>
            </div>
        </div>
    </div>
</template>

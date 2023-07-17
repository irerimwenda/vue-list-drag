<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {reactive} from "vue";

const props = defineProps(['userProjects'])

const form = reactive({
    title: '',
});

const projects = props.userProjects;

function createProject() {
    axios.post('/projects/save', form)
        .then(() => {
            form.title = '';
            getProjects();
            alert('Project created!')
        })
        .catch(error => {
            alert(error);
        });
}

function getProjects() {
    axios.get('/projects/fetch')
        .then(response => {
            projects.value = response.data;
            window.location.reload();
        })
        .catch(error => {
            alert(error);
        });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900">
                        <p class="mb-3">You're logged in!</p>

                        <div>
                            <h1 class="mb-4" v-if="projects.length > 0">Add another project</h1>
                            <h1 class="mb-4" v-else>Create your first project</h1>
                        </div>

                        <form @submit.prevent="createProject">
                            <div class="flex mb-3">
                                <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline me-3" v-model="form.title"/>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Project</button>
                            </div>
                        </form>

                        <div v-if="projects.length > 0">
                            <h1 class="mt-6 mb-3 text-xl font-bold underline">Your projects</h1>

                            <div class="grid gap-4 grid-cols-4">
                                <div class="block max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow" v-for="(project, index) in projects" :key="index">
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-dark mb-2">{{ project.title }}</h5>
                                    <a :href="`/projects/${project.id}/tasks`" class="text-blue-500 hover:text-blue-700">View Tasks</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

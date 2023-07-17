<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {reactive, ref} from "vue";
import { VueDraggable } from 'vue-draggable-plus'
import { Modal } from 'flowbite';

const props = defineProps(['project', 'otherProjects', 'userTasks'])

const form = reactive({
    title: '',
    taskId: ''
});

const tasks = props.userTasks;
let modalElement = ref(null);

function createTask() {
    axios.post(`/${props.project.id}/tasks/save`, form)
        .then(() => {
            form.title = '';
            getTasks();
            alert('Task created!')
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function getTasks() {
    axios.get(`/${props.project.id}/tasks/fetch`)
        .then(response => {
            tasks.value = response.data;
            window.location.reload();
        })
        .catch(error => {
            alert(error);
        });
}

function editTask(task) {
    console.log(task)
    form.title = task.title;
    form.taskId = task.id;
    modalElement = new Modal(document.getElementById('editTaskModal'), {});
    modalElement.show();
}

function updateTask() {
    axios.post(`/${form.taskId}/tasks/update`, form)
        .then(() => {
            getTasks();
            modalElement.hide();
            alert('Task updated!')
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function updatePriority(event) {
    console.log(event)
    let data = {
        itemOldDragIndex: event.oldDraggableIndex,
        itemNewDragIndex: event.newDraggableIndex
    }

    axios.post(`/${props.project.id}/tasks/update-priority`, data)
        .then(() => {
            getTasks();
        })
        .catch(error => {
            alert(error.response.data.message);
        });
}

function deleteTask(taskId) {
    if (confirm('Are you sure you want to delete this task?')) {
        axios.delete(`/${taskId}/tasks/destroy`)
            .then(() => {
                getTasks();
                alert('Task deleted!')
            })
            .catch(error => {
                alert(error.response.data.message);
            });
    }
}

function hideModal() {
    modalElement.hide();
}

function switchProject(event) {
    window.location.href = `/projects/${event.target.value}/tasks`;
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight grow">{{ project.title }}</h2>

                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Switch Project</label>
                    <select @change="switchProject" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>{{ project.title }}</option>
                        <option :value="anotherProject.id" v-for="anotherProject in otherProjects" :key="anotherProject.id">{{ anotherProject.title }}</option>
                    </select>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900">
                        <div>
                            <h1 class="mb-4" v-if="tasks.length > 0">Add another task</h1>
                            <h1 class="mb-4" v-else>Create your first task</h1>
                        </div>

                        <form @submit.prevent="createTask">
                            <div class="flex mb-3">
                                <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline me-3" v-model="form.title"/>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Task</button>
                            </div>
                        </form>

                        <div v-if="tasks.length > 0">
                            <h1 class="mt-6 mb-3 text-xl font-bold underline">Your Tasks</h1>

                            <VueDraggable ref="el" v-model="tasks" chosenClass="shadow" ghostClass="bg-gray-100" @update="updatePriority">
                                <div v-for="task in tasks" :key="task.id" class="p-3 mb-2 cursor border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="grow">
                                            {{ task.title }}
                                        </div>

                                        <div>
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mr-3" @click="editTask(task)">Edit</button>
                                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full" @click="deleteTask(task.id)">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </VueDraggable>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Task Modal -->
            <div id="editTaskModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <form @submit.prevent="updateTask">
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Update Task
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="hideModal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <div class="p-6 space-y-6">
                                <input class="shadow appearance-none border border-red-500 rounded w-full px-3 text-gray-700 focus:outline-none focus:shadow-outline" v-model="form.title"/>
                            </div>
                            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                                <button @click="hideModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    .cursor {
        cursor: pointer;
    }
</style>

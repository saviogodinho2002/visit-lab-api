<script setup lang="js">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm, Link} from '@inertiajs/vue3';

import PrimaryButton from "@/Components/PrimaryButton.vue";

import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {toRefs,ref} from "vue";


const props = defineProps({
    applicationLogs: Array,

})

const confirmModal = ref(false);


const form = useForm({
    id: null,
});
const {id} = toRefs(form);

const setConfirmModal = (value, idf = null) => {
    confirmModal.value = value;
    id.value = idf
};

const errorModal = ref(false);
const errorMessage = ref("")
const setErrorModal = (value) => {
    errorModal.value = value;

};
/*function destroy() {
    confirmModal.value = false;
    console.log(id.value)
    form.delete(route('city.destroy',form.id), {
        onSuccess: () =>{
            //form.reset()
        },
        onError:(response)=>{
            errorMessage.value = response.message;
            setErrorModal(true);
        }
    });


}*/

</script>

<template>
    <Head title="Log das aplicações" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold  dark:text-white text-xl text-gray-800 leading-tight"> Log das aplicações</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900  dark:text-gray-100"> Log das aplicações</div>
<!--                    <div class="flex justify-end w-full mb-3 mt-5 ">
                        <Link  :href="route('city.create')"
                               class="mr-5 mt-auto mb-auto inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded h-2/3">
                            Adicionar nova Cidade
                        </Link>
                    </div>-->

                    <div class="mb-3 mx-3">

                        <div class="w-full flex flex-col">
                            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden ">
                                        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                                            <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>

                                                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                    Aplicação
                                                </th>
                                                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                    Metodo
                                                </th>
                                                <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                    Path
                                                </th>

<!--                                                <th scope="col" class="p-4">
                                                    <span class="sr-only">Edit</span>
                                                </th>-->
<!--                                                <th scope="col" class="p-4">
                                                    <span class="sr-only">Excluir</span>
                                                </th>-->

                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                                            <tr v-for="applicationLog in applicationLogs" class="hover:bg-gray-100 dark:hover:bg-gray-700">

                                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{applicationLog.application.name}}</td>
                                                <td class="py-4 px-6 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">{{applicationLog.method}}</td>
                                                <td class="py-4 px-6 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">{{applicationLog.path}}</td>

<!--                                                <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                                                    <Link :href="route('city.edit',applicationLog)" class="text-blue-600 dark:text-blue-500 hover:underline">editar</Link>
                                                </td>-->
<!--                                                <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                                                    <button @click="setConfirmModal(true,city)" class="text-red-500 hover:underline">excluir</button>

                                                </td>-->
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <Modal :show="errorModal" >
            <div class="p-6">

                <h2 class="mx-auto text-red-500 text-center text-3xl font-medium ">
                    <svg width="30px" class="mx-auto "
                         fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Erro
                </h2>

                <p class="text-center mt-1 text-sm text-2xl text-gray-600">
                    {{errorMessage}}
                </p>


                <div class="mt-6 flex justify-center">
                    <PrimaryButton @click="setErrorModal(false)"> Ok </PrimaryButton>


                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

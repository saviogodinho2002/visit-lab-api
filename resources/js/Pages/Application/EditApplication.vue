<script setup lang="js">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';

import PrimaryButton from "@/Components/PrimaryButton.vue";

import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {nextTick, reactive, ref, toRefs, watch} from "vue";

import Modal from "@/Components/Modal.vue";


const props = defineProps({
    application: Array,
})


const form = useForm({
    state_id:"",
    name:props.application.name,
    acronym:"",


})


function showPorto(){
    console.log(form.data())
}

const errorModal = ref(false);
const errorMessage = ref("")
const setErrorModal = (value) => {
    errorModal.value = value;

};

const submit = () => {
    form.put(route('applications.update',props.application), {
        onSuccess: () =>{
            //form.reset()
        },
        onError:(response)=>{
            errorMessage.value = response.message;
            setErrorModal(true);
        }
    });
};


</script>
<style scoped>
#preview {
    max-width: 500px;
    max-height: 300px;

}
@media only screen and (max-width: 992px) {
    #preview {
        max-width: 100%;


    }
}

</style>

<template>
    <Head title="Cidades" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold  dark:text-white text-xl text-gray-800 leading-tight">Nova Aplicação</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900  dark:text-gray-100">Aplicações</div>

                    <form class="p-6" @submit.prevent="submit">

                        <div class="mt-3">
                            <InputLabel for="name" value="Nome" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>



                        <div class="flex items-center justify-end mt-4">


                            <PrimaryButton   class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Criar Aplicação
                            </PrimaryButton>
                        </div>
                    </form>

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

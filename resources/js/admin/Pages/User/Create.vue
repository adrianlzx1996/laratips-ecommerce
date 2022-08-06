<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Container from '../../Components/Container.vue';
import Card from '../../Components/Card/Card.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Button from "../../Components/Button.vue";
import InputGroup from "../../Components/InputGroup.vue";
import { onMounted } from "vue";
import SelectGroup from "../../Components/SelectGroup.vue";

const form = useForm({
    name: '',
    email: '',
    password: '',
    passwordConfirmation: '',
    roleId: '',
});

const submit = () => {
    props.edit
        ? form.put(route(`admin.${props.routeResourceName}.update`, {id: props.item.id, name: form.name}))
        : form.post(route(`admin.${props.routeResourceName}.store`), {
            onSuccess: () => {
                form.reset('name')
                form.reset('email')
                form.reset('password')
                form.reset('passwordConfirmation')
                form.reset('roleId')
            },
        });
}

onMounted(() => {
    if (props.edit) {
        form.name = props.item.name;
        form.email = props.item.email;
        form.roleId = props.item.roles[0].id;
    }
});

const props = defineProps({
    title: {
        type: String,
        default: 'Add Permission'
    },
    edit: {
        type: Boolean,
        default: false,
    },
    item: {
        type: Object,
        default: () => ({}),
    },
    routeResourceName: {
        type: String,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <Head :title="title"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ title }}
            </h2>
        </template>

        <Container>
            <Card>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-6">
                        <InputGroup v-model="form.name" :error-message="form.errors.name" label="Name" required/>
                        <InputGroup v-model="form.email" :error-message="form.errors.email" label="Email" required
                                    type="email"/>
                        <InputGroup v-model="form.password" :error-message="form.errors.password" :required="!edit"
                                    label="Password" type="password"/>
                        <InputGroup v-model="form.passwordConfirmation"
                                    :error-message="form.errors.passwordConfirmation" :required="!edit"
                                    label="Confirm Password" type="password"/>

                        <SelectGroup v-model="form.roleId" :error-message="form.errors.roleId"
                                     :items="roles"
                                     label="Role" required type="password"/>
                    </div>

                    <div class="mt-4">
                        <Button :disabled="form.processing">
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </Button>
                    </div>
                </form>
            </Card>
        </Container>
    </BreezeAuthenticatedLayout>
</template>

<script>
export default {
    name: "Create"
}
</script>

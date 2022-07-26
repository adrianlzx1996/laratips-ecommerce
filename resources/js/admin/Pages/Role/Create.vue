<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Container from '../../Components/Container.vue';
import Card from '../../Components/Card/Card.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Button from "../../Components/Button.vue";
import BreezeInput from '../../Components/Input.vue';
import BreezeLabel from '../../Components/Label.vue';
import InputError from "../../Components/InputError.vue";
import { onMounted } from "vue";
import Permissions from "./Permissions.vue";

const form = useForm({
    name: ''
});

const submit = () => {
    props.edit
        ? form.put(route(`admin.${props.routeResourceName}.update`, {id: props.item.id, name: form.name}))
        : form.post(route(`admin.${props.routeResourceName}.store`), {
            onFinish: () => form.reset('name'),
        });
}

onMounted(() => {
    if (props.edit) {
        form.name = props.item.name
    }
});

const props = defineProps({
    title: {
        type: String,
        default: 'Add Role'
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
    permissions: {
        type: Array,
        default: () => [],
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
                    <div>
                        <BreezeLabel for="name" value="Name"/>
                        <BreezeInput id="name" v-model="form.name" autocomplete="name" autofocus
                                     class="mt-1 block w-full"
                                     required type="text"/>

                        <InputError :message="form.errors.name" class="mt-1"/>
                    </div>

                    <div class="mt-4">
                        <Button :disabled="form.processing">
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </Button>
                    </div>
                </form>
            </Card>
        </Container>

        <Permissions v-if="edit" :permissions="permissions" :role="item"/>
    </BreezeAuthenticatedLayout>
</template>

<script>
export default {
    name: "Create"
}
</script>

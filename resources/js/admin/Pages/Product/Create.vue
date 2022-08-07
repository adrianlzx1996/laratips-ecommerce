<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Container from '../../Components/Container.vue';
import Card from '../../Components/Card/Card.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Button from "../../Components/Button.vue";
import InputGroup from "../../Components/InputGroup.vue";
import { onMounted, watch } from "vue";
import { kebabCase, replace } from "lodash";
import CheckboxGroup from "../../Components/CheckboxGroup.vue";

const form = useForm({
    name: '',
    slug: '',
    active: true,
});

watch(() => form.name, name => {
    if (!props.edit) {
        form.slug = kebabCase(replace(name, /&./, "and"))
    }
})

const submit = () => {
    props.edit
        ? form.put(route(`admin.${props.routeResourceName}.update`, {id: props.item.id, name: form.name}))
        : form.post(route(`admin.${props.routeResourceName}.store`), {
            onSuccess: () => {
                form.reset('name')
                form.reset('slug')
                form.reset('active')
            },
        });
}

onMounted(() => {
    if (props.edit) {
        form.name = props.item.name;
        form.slug = props.item.slug;
        form.active = props.item.active;
    }
});

const props = defineProps({
    title: {
        type: String,
        default: 'Add Product'
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
                        <InputGroup v-model="form.slug" :error-message="form.errors.slug" label="Slug" required/>

                        <div class="mt-6">
                            <CheckboxGroup v-model:checked="form.active" :error-message="form.errors.active"
                                           label="Active"/>
                        </div>
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

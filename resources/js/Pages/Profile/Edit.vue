<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- PROFILE INFORMATION CARD -->
                <div class="bg-slate-900 border border-slate-700 rounded-lg p-8 shadow-sm">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- PASSWORD UPDATE CARD -->
                <div class="bg-slate-900 border border-slate-700 rounded-lg p-8 shadow-sm">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- DELETE ACCOUNT CARD -->
                <div class="bg-slate-900 border border-slate-700 rounded-lg p-8 shadow-sm">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

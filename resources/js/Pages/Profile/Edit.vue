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
        <div class="min-h-screen bg-black overflow-hidden relative py-12">
            <!-- Grid Background -->
            <div class="fixed inset-0 opacity-5 pointer-events-none" style="background-image: linear-gradient(0deg, transparent 24%, rgba(0, 136, 204, 0.1) 25%, rgba(0, 136, 204, 0.1) 26%, transparent 27%, transparent 74%, rgba(0, 136, 204, 0.1) 75%, rgba(0, 136, 204, 0.1) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(0, 136, 204, 0.1) 25%, rgba(0, 136, 204, 0.1) 26%, transparent 27%, transparent 74%, rgba(0, 136, 204, 0.1) 75%, rgba(0, 136, 204, 0.1) 76%, transparent 77%, transparent); background-size: 60px 60px;"></div>

            <div class="relative z-10 mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- PROFILE INFORMATION CARD -->
                <div
                    class="border-4 border-cyan-400 bg-black p-8 shadow-lg"
                    style="box-shadow: 0 0 30px rgba(0, 136, 204, 0.6), inset 0 0 15px rgba(0, 136, 204, 0.1);"
                >
                    <!-- TOP BORDER EFFECT -->
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-transparent via-cyan-400 to-transparent" style="box-shadow: 0 0 20px #0088CC;" ></div>

                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- PASSWORD UPDATE CARD -->
                <div
                    class="border-4 border-cyan-400 bg-black p-8 shadow-lg"
                    style="box-shadow: 0 0 30px rgba(0, 136, 204, 0.6), inset 0 0 15px rgba(0, 136, 204, 0.1);"
                >
                    <!-- TOP BORDER EFFECT -->
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-transparent via-cyan-400 to-transparent" style="box-shadow: 0 0 20px #0088CC;"></div>

                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- DELETE ACCOUNT CARD -->
                <div
                    class="border-4 border-cyan-400 bg-black p-8 shadow-lg"
                    style="box-shadow: 0 0 30px rgba(0, 136, 204, 0.6), inset 0 0 15px rgba(0, 136, 204, 0.1);"
                >
                    <!-- TOP BORDER EFFECT -->
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-transparent via-cyan-400 to-transparent" style="box-shadow: 0 0 20px #0088CC;"></div>

                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

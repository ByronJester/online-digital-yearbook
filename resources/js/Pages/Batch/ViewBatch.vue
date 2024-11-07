<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';

const loading = ref(false)

defineProps({
    batch: {
        type: Object,
    },
    students: {
        type: Array
    }
});

// Form data for new post
const student_name = ref('');
const award = ref('');
const image = ref('');

const saveStudent = (id) => {
    swal({
        title: "Are you sure to add this alumni to this batch?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            const formData = new FormData();

            formData.append('batch_id', id);
            formData.append('image', image.value);
            formData.append('student_name', student_name.value);
            formData.append('award', award.value);

            setTimeout(() => {
                Inertia.post(route('staff-save-batch-student'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onSuccess: (page) => {
                        // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                        // location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        // alert('There was an error uploading the file.');
                        loading.value = false
                    },
                });

            }, 1000);

        }
    });

};

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png')

const back = () => {
    Inertia.get(route('alumni-class-batches'))
}

const deleteAlumni = (id) => {
    swal({
        title: "Are you sure to delete this alumni?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            const formData = new FormData();
            formData.append('id', id);

            Inertia.post(route('staff-delete-batch-student'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onSuccess: (page) => {
                    // location.reload()
                    // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                    loading.value = false
                },
                onError: (errors) => {
                    // alert('There was an error uploading the file.');
                    loading.value = false
                },
            });


        }
    });
}

</script>

<template>
    <Head title="Batch" />

    <AuthenticatedLayout>
        <div class="w-full">
            <div class="w-full mb-5">
                <button class="rounded-md bg-blue-500 px-5 py-1 text-white text-xl" @click="back"> Back</button>
            </div>
            <div class="w-full mb-5">
                <p class="text-2xl font-bold">{{batch.course}} - {{batch.school_year}} (Section {{ batch.section }})</p>
            </div>
            <div class="w-full h-[80vh] ">
                <div class="mb-10 border-2 border-black rounded-md p-5" v-if="$page.props.auth.user.user_type == 'school_staff'">
                    <div class="w-full flex justify-center items-center" v-if="loading">
                        <VueSpinner size="50" color="red" />
                    </div>

                    <div class="w-full" v-else>
                        <div class="w-full flex flex-col md:flex-row py-3">
                            <div class="w-full md:mr-1">
                                <label>Alumni Name</label>
                                <br>
                                <input type="text" class="w-full rounded-md" v-model="student_name"/>
                            </div>

                            <div class="w-full md:mr-1">
                                <label>Alumni Award</label>
                                <br>
                                <input type="text" class="w-full rounded-md" v-model="award"/>
                            </div>

                            <div class="w-full md:mr-1">
                                <label>Alumni Picture</label>
                                <br>
                                <input type="file" class="w-full rounded-md" @change="(e) => image = e.target.files[0]"/>
                            </div>
                        </div>

                        <button @click="saveStudent(batch.id)" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Save</button>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4">
                    <div class="w-full h-[300px] border border-black rounded-md cursor-pointer" v-for="student in students">
                        <p v-if="$page.props.auth.user.user_type == 'school_staff'">
                            <span class="float-right text-red-400 m-2 text-xs"
                                @click="deleteAlumni(student.id)"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </span>
                        </p>
                        <img :src="student.image || logoUrl" class="w-full h-[200px]"/>
                        <p class="text-center font-bold">{{ student.student_name }}</p>
                        <p class="text-center text-xs">{{ student.award }}</p>
                    </div>
                </div>
            </div>


        </div>



    </AuthenticatedLayout>
</template>

<style scoped>
textarea {
    resize: none;
}

.scroll-container {
    overflow: hidden; /* Hides the scrollbar */
    overflow-y: auto; /* Enables vertical scrolling */
    scrollbar-width: none; /* For Firefox */
    -ms-overflow-style: none; /* For IE and Edge */
}

/* Hides the scrollbar in WebKit-based browsers (Chrome, Safari) */
.scroll-container::-webkit-scrollbar {
    display: none;
}
</style>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';

const loading = ref(false)

defineProps({
    courses: {
        type: Array,
    },
});

// console.log(users)

const handleTableAction = ({ action, row }) => {
    if (action == 'view') {
        console.log('view')
    } else if (action == 'edit') {
        courseId.value = row.id
        courseName.value = row.name
        console.log(row)
    } else if (action == 'delete') {
        swal({
            title: "Are you sure to delete this course?",
            text: "",
            icon: "success",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {

                try {
                    Inertia.post(route('user-delete-course'), {id: row.id}, {
                        onSuccess: (page) => {
                            // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                            location.reload()
                        },
                        onError: (errors) => {
                            // alert('There was an error uploading the file.');
                        },
                    });
                } catch (error) {
                    alert('There was an error uploading the file.');
                }

            }
        });
    } else if (action == 'copy') {

    } else if(action == 'archive') {

    }
}

const downloadFile = (filename) => {
    const link = document.createElement('a');
    link.href = `/files/${filename}`;
    link.download = filename;
    link.click();
}

const fileInput = ref(null);

const handleFileUpload  = (event) => {
    swal({
        title: "Are you sure to add this users?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            const file = event.target.files[0];

            const formData = new FormData();
            formData.append('file', file);

            try {
                setTimeout(() => {
                    Inertia.post(route('upload-users'), formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                        onSuccess: (page) => {
                            // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                            loading.value = false
                        },
                        onError: (errors) => {
                            // alert('There was an error uploading the file.');
                            loading.value = false
                        },
                    });

                    }, 1000);


            } catch (error) {
                alert('There was an error uploading the file.');
                loading.value = false
            }

        }
    });


};

const triggerFileUpload = () => {
    fileInput.value.click();
}

const courseId = ref('')
const courseName = ref('')

const saveCourse = () => {
    // swal({
    //     title: "Are you sure to save this course?",
    //     text: "",
    //     icon: "success",
    //     buttons: true,
    //     dangerMode: true,
    // })
    // .then((proceed) => {
    //     if (proceed) {

    //         try {
    //             Inertia.post(route('user-save-course'), {id: id.value, course: course.value}, {
    //                 onSuccess: (page) => {
    //                     // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
    //                     location.reload()
    //                 },
    //                 onError: (errors) => {
    //                     // alert('There was an error uploading the file.');
    //                 },
    //             });
    //         } catch (error) {
    //             alert('There was an error uploading the file.');
    //         }

    //     }
    // });

    Inertia.post(route('user-save-course'), {id: courseId.value, course: courseName.value}, {
        onSuccess: (page) => {
            // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
            location.reload()
        },
        onError: (errors) => {
            // alert('There was an error uploading the file.');
        },
    });
}

const batches = ref(null)

</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <div class="w-full p-5 ">
            <div class="w-full border border-black rounded-md" style="margin-bottom: 20px;">
                <div class="w-full p-10">
                    <input type="text" placeholder="Course..." v-model="courseName" class="w-full rounded-md"/>

                    <input type="text" placeholder="Course..." v-model="courseName" class="w-full rounded-md"/>

                    <button class="bg-blue-500 py-2 px-7 rounded-md mt-5 text-white"
                        @click="saveCourse"
                    >
                        Save
                    </button>
                </div>



            </div>

            <div class="w-full">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                </div>

                <div class="w-full" v-else>
                    <Table
                        :headers="['Name']"
                        :rows="courses"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showArchive="false"
                        :showSearch="true"
                        @action-event="handleTableAction"
                    />
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
.download-btn {
    background: #04549C;
    color: white;
    padding: 10px;
    border-radius: 10px;
    font-size: 12px;
    float: right;
    margin-right: 2px;
}

.upload-btn {
    background: #04549C;
    color: white;
    padding: 10px;
    border-radius: 10px;
    font-size: 12px;
}

</style>

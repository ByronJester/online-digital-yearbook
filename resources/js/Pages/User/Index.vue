<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm} from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';

const loading = ref(false)

defineProps({
    users: {
        type: Array,
    },
    courses: {
        type: Array
    }
});

// console.log(users)

const handleTableAction = ({ action, row }) => {
    if (action == 'view') {
        console.log('view')
    } else if (action == 'edit') {
        console.log('edit')
    } else if (action == 'delete') {
        swal({
            title: "Are you sure to delete this user?",
            text: "",
            icon: "success",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {

                try {
                    Inertia.post(route('delete-user'), {id: row.id}, {
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
        const password = row.password_text;
        console.log(password)
        navigator.clipboard.writeText(password).then(() => {
            alert('Password Copied.')
        }).catch(err => {
            console.error('Failed to copy password: ', err);
        });
    } else if(action == 'archive') {
        swal({
            title: "Are you sure to archive this user?",
            text: "",
            icon: "success",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {

                try {
                    Inertia.post(route('delete-user'), {id: row.id}, {
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

const alumniForm = useForm({
    first_name: '',
    middle_name: '',
    last_name: '',
    contact: '',
    email: '',
    school_id_no: '',
    class_batch: '',
    program: '',
    section: '',
    user_type: 'school_alumni',
    alumni_picture: '',
    status: 'paid'
});

const saveUser = () => {
    swal({
        title: "Are you sure to add this user?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            alumniForm.post(route('save-user'), {
                onSuccess: (page) => {
                    location.reload()
                },
                onError: (errors) => {
                    swal("Error saving.");
                }
            });

        }
    });
}

const staffForm = useForm({
    first_name: '',
    middle_name: '',
    last_name: '',
    contact: '',
    email: '',
    position: '',
    user_type: 'school_staff'
});

const saveStaff = () => {
    swal({
        title: "Are you sure to add this user?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            staffForm.post(route('save-user'), {
                onSuccess: (page) => {
                    location.reload()
                },
                onError: (errors) => {
                    swal("Error saving.");
                }
            });

        }
    });
}

const addAlumni = ref(false)
const addStaff = ref(false)

</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <div class="w-full p-5 ">
            <div class="w-full" style="margin-bottom: 20px;">
                <input type="file" ref="fileInput" @change="handleFileUpload" accept=".xlsx" style="display: none;" />

                <button class="upload-btn" @click="triggerFileUpload()">
                    Upload Users
                </button>

                <button class="download-btn" @click="downloadFile('alumni_format.xlsx')" v-if="$page.props.auth.user.user_type == 'school_staff'">
                    Download Alumni Format
                </button>

                <button class="download-btn" @click="downloadFile('staff_format.xlsx')" v-if="$page.props.auth.user.user_type == 'system_admin'">
                    Download Staff Format
                </button>

                <button class="download-btn" @click="addAlumni = !addAlumni" v-if="$page.props.auth.user.user_type == 'school_staff'">
                    {{!addAlumni ? 'Add Alumni' : 'Close'}}
                </button>

                <button class="download-btn" @click="addStaff = !addStaff" v-if="$page.props.auth.user.user_type == 'system_admin'">
                    {{!addStaff ? 'Add Account' : 'Close'}}
                </button>
            </div>

            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4 bg-white rounded-md border border-black my-10" v-if="$page.props.auth.user.user_type == 'school_staff' && addAlumni">
                <div class="w-full p-2">
                    <label>First Name</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="alumniForm.first_name" placeholder="First Name"/>
                </div>

                <div class="w-full p-2">
                    <label>Middle Name</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="alumniForm.middle_name" placeholder="Middle Name (Optional)"/>
                </div>


                <div class="w-full p-2">
                    <label>Last Name</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="alumniForm.last_name" placeholder="Last Name"/>
                </div>

                <div class="w-full p-2">
                    <label>Contact</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="alumniForm.contact" placeholder="Contact"/>
                </div>

                <div class="w-full p-2">
                    <label>Email</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="alumniForm.email" placeholder="Email"/>
                </div>


                <div class="w-full p-2">
                    <label>Alumni ID No.</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="alumniForm.school_id_no" placeholder="Alumni ID No."/>
                </div>

                <div class="w-full p-2">
                    <label>Program</label>
                    <br>
                    <select class="rounded-md w-full" v-model="alumniForm.program">
                        <option v-for="c in courses" :key="c.id" :value="c.name"> {{ c.name }}</option>
                    </select>
                </div>

                <div class="w-full p-2">
                    <label>Section</label>
                    <br>
                    <select class="rounded-md w-full" v-model="alumniForm.section">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="3">4</option>
                    </select>
                </div>

                <div class="w-full p-2">
                    <label>Batch</label>
                    <br>
                    <select class="rounded-md w-full" v-model="alumniForm.class_batch">
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="w-full p-2">
                    <label>Award</label>
                    <br>
                    <input type="text" v-model="alumniForm.award" class="w-full rounded-md" />
                </div>

                <div class="w-full p-2">
                    <label>Status</label>
                    <br>
                    <select class="rounded-md w-full" v-model="alumniForm.status">
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>

                    </select>
                </div>

                <div class="w-full p-2">
                    <label>Alumni Picture</label>
                    <br>
                    <input type="file" @change="(e) => alumniForm.alumni_picture = e.target.files[0]" class="w-full rounded-md border border-black" />
                </div>



                <div class="w-full p-2">
                    <button class="w-full py-2 px-4 bg-blue-500 rounded-md text-white mt-5"
                        @click="saveUser"
                    >
                        Save
                    </button>
                </div>
            </div>

            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 bg-white rounded-md border border-black my-10" v-if="$page.props.auth.user.user_type == 'system_admin' && addStaff">
                <div class="w-full p-2">
                    <label>First Name</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="staffForm.first_name"/>
                </div>

                <div class="w-full p-2">
                    <label>Middle Name</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="staffForm.middle_name"/>
                </div>

                <div class="w-full p-2">
                    <label>Last Name</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="staffForm.last_name"/>
                </div>

                <div class="w-full p-2">
                    <label>Contact</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="staffForm.contact"/>
                </div>

                <div class="w-full p-2">
                    <label>Email</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="staffForm.email"/>
                </div>

                <div class="w-full p-2">
                    <label>Position</label>
                    <br>
                    <input type="text" class="w-full rounded-md" v-model="staffForm.position"/>
                </div>

                <div class="w-full p-2">
                    <label>Role</label>
                    <br>
                    <select v-model="staffForm.user_type" class="w-full rounded-md">
                        <option :value="'school_staff'">School Staff</option>
                        <option :value="'system_admin'">Admin</option>
                    </select>
                </div>

                <div class="w-full p-2">
                    <button class="w-full py-2 px-4 bg-blue-500 rounded-md text-white mt-5"
                        @click="saveStaff"
                    >
                        Save
                    </button>
                </div>
            </div>

            <div class="w-full">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                </div>

                <div class="w-full" v-if="!loading && $page.props.auth.user.user_type == 'system_admin'">
                    <Table
                        :headers="['First Name', 'Middle Name', 'Last Name', 'Email', 'Role']"
                        :rows="users"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="false"
                        :showDelete="false"
                        :showCopy="false"
                        :showArchive="$page.props.auth.user.user_type == 'system_admin'"
                        :showSearch="true"
                        @action-event="handleTableAction"
                    />
                </div>

                <div class="w-full" v-if="!loading && $page.props.auth.user.user_type == 'school_staff'">
                    <Table
                        :headers="['First Name', 'Middle Name', 'Last Name', 'Email', 'Program', 'Class Batch']"
                        :rows="users"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="false"
                        :showDelete="false"
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    users: {
        type: Array,
    },
});

// console.log(users)

const handleTableAction = ({ action, row }) => {
    console.log(action)
    console.log(row)

    if (action == 'view') {
        console.log('view')
    } else if (action == 'edit') {
        console.log('edit')
    } else if (action == 'delete') {
        console.log('delete')
    } else if (action == 'copy') {
        const password = row.password_text;
        console.log(password)
        navigator.clipboard.writeText(password).then(() => {
            alert('Password Copied.')
        }).catch(err => {
            console.error('Failed to copy password: ', err);
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

const handleFileUpload  = async(event) => {
    const file = event.target.files[0];

    const formData = new FormData();
    formData.append('file', file);

    try {
        await Inertia.post(route('upload-users'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: (page) => {
                alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
            },
            onError: (errors) => {
                alert('There was an error uploading the file.');
            },
        });
    } catch (error) {
        alert('There was an error uploading the file.');
    }

};

const triggerFileUpload = () => {
    fileInput.value.click();
}
</script>

<template>
    <!-- <Head title="User Management" /> -->

    <AuthenticatedLayout>
        <div class="w-full p-5 ">
            <div class="w-full" style="margin-bottom: 20px;">
                <input type="file" ref="fileInput" @change="handleFileUpload" accept=".xlsx" style="display: none;" />

                <button class="upload-btn" @click="triggerFileUpload()">
                    Upload Users
                </button>

                <button class="download-btn" @click="downloadFile('alumni_format.xlsx')">
                    Download Alumni Format
                </button>

                <button class="download-btn" @click="downloadFile('staff_format.xlsx')">
                    Download Staff Format
                </button>
            </div>

            <div class="w-full">
                <Table
                    :headers="['First Name', 'Middle Name', 'Last Name', 'Email', 'Role']"
                    :rows="users"
                    :rows-per-page="10"
                    :showView="false"
                    :showEdit="false"
                    :showDelete="false"
                    :showCopy="true"
                    @action-event="handleTableAction"
                />
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style>
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

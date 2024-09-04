<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';

var tableData = [
    { name: 'John Doe', age: 28, email: 'john@example.com' },
    { name: 'Jane Smith', age: 34, email: 'jane@example.com' },
    { name: 'Sam Johnson', age: 23, email: 'sam@example.com' },
];

const handleTableAction = ({ action, row }) => {
    console.log(action)
    console.log(row)

    if (action == 'view') {
        console.log('view')
    } else if (action == 'edit') {
        console.log('edit')
    } else if (action == 'delete') {
        console.log('delete')
    }
}

const downloadFile = (filename) => {
    const link = document.createElement('a');
    link.href = `/files/${filename}`;
    link.download = filename;
    link.click();
}
</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <div class="w-full p-5 ">
            <div class="w-full">
                <button class="download-btn" @click="downloadFile('alumni_format.xlsx')">
                    Download Alumni Format
                </button>

                <button class="download-btn" @click="downloadFile('staff_format.xlsx')">
                    Download Staff Format
                </button>
            </div>

            <div class="w-full">
                <Table
                    :headers="['Name', 'Age', 'Email']"
                    :rows="tableData"
                    :rows-per-page="10"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
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

</style>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';
import axios from 'axios';

const loading = ref(false)
const table = ref('');
const file = ref(null)
const tableName = ref('')
const tableUpload = ref('')

const props = defineProps({
    tables: {
        type: Array,
    },
});

const downloadTable = async() => {
    const tableName = table.value;
    const response = await axios.get(route('bar', tableName), {
        responseType: 'blob',
    });

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `${tableName}.sql`);
    document.body.appendChild(link);
    link.click();
}

const handleFileUpload = (event) => {
    const selectedFile = event.target.files[0];
    file.value = event.target.files[0];

    tableName.value = removeWordFromString(selectedFile.name, '.sql')
};

const uploadFile = async () => {
    if (!file.value) {
        alert('No file selected!');
        return;
    }

    const formData = new FormData();
//   const fileInput = this.$refs.fileInput;
    formData.append('file', file.value);
    formData.append('table', tableUpload.value);

  try {
    // Replace '/upload-endpoint' with your actual endpoint
    const response = await axios.post(route('bar-upload'), formData)
        .then(function (response) {
            // console.log(response);
            alert('Restored successfully.')
            location.reload()
        })
        .catch(function (error) {
            // console.log(error);
        });

    if (response.ok) {
      console.log('File uploaded successfully.');
      // Handle success
    } else {
      console.error('File upload failed.');
      // Handle error
    }
  } catch (error) {
    console.error('Error uploading file:', error);
  }
};

const removeWordFromString = (originalString, wordToRemove) => {
    // Create a regular expression to match the word globally, with word boundaries
    const regex = new RegExp(`\\b${wordToRemove}\\b`, 'gi');
    // Replace the matched word with an empty string
    return originalString.replace(regex, '').trim().replace(/\s+/g, ' '); // Trim spaces and replace multiple spaces with a single space
}

const handleTableAction = ({ action, row }) => {
    console.log(row)
    console.log(action)

    if(action == 'backup') {
        const tableName = row.entity;
        const response = axios.get(route('bar', tableName), {
            responseType: 'blob',
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `${tableName}.sql`);
        document.body.appendChild(link);
        link.click();
    }
}


</script>

<template>
    <Head title="Backup and Restore" />

    <AuthenticatedLayout>
        <div class="w-full p-5 ">
            <!-- <div class="w-full">
                <p class="text-2xl mb-10">
                    Backup and Restore
                </p>

                <div class="w-full">
                    <p class="text-2xl mb-5">Backup</p>
                    <select v-model="table" class="rounded-md mr-2">
                        <option :value="t" v-for="t in tables" :key="t">
                            {{ t }}
                        </option>
                    </select>
                    <button @click="downloadTable" class="rounded-md bg-blue-500 p-2 px-4 text-white">
                        Backup
                    </button>
                </div>
            </div>

            <div class="w-full mt-20">
                <p class="text-2xl mb-5">Restore</p>
                <select v-model="tableUpload" class="rounded-md mr-2">
                    <option :value="t" v-for="t in tables" :key="t">
                        {{ t }}
                    </option>
                </select>
                <input type="file" @change="handleFileUpload" accept=".sql" class="border border-black rounded-md mr-1 py-1"/>
                <button @click="uploadFile" class="rounded-md bg-blue-500 p-2 px-4 text-white">Restore</button>
            </div> -->


            <div class="w-full flex flex-col">
                <p class="font-bold text-2xl">
                    Backup
                </p>
                <div class="w-full mt-3">
                    <Table
                        :headers="['Entity', 'Description']"
                        :rows="tables"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="false"
                        :showDelete="false"
                        :showCopy="false"
                        :showArchive="false"
                        :showSearch="false"
                        :showBackup="true"
                        @action-event="handleTableAction"
                    />
                </div>

            </div>

            <div class="w-full mt-5">
                <p class="font-bold text-2xl">
                    Restore
                </p>

                <div class="w-full mt-3">
                    <select v-model="tableUpload" class="rounded-md mr-2">
                        <option :value="t" v-for="t in tables" :key="t">
                            {{ t.entity }}
                        </option>
                    </select>
                    <input type="file" @change="handleFileUpload" accept=".sql" class="border border-black rounded-md mr-1 py-1"/>
                    <button @click="uploadFile" class="rounded-md bg-blue-500 p-2 px-4 text-white">Restore</button>
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

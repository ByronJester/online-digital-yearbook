<template>
    <div class="responsive-table">
        <input
            v-if="showSearch"
            type="text"
            v-model="searchQuery"
            placeholder="Search..."
            class="search-input"
        />

        <table>
            <thead>
                <tr>
                    <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
                    <th v-if="showView || showEdit || showDelete || showCopy || showArchive || showUnarchive || showBackup">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in paginatedData" :key="index">
                    <td v-for="header in headers" :key="header" :data-label="header" class="w-[300px]">
                        <!-- <template v-if="isImage(row[header.toLowerCase().replace(/ /g, '_')])">
                            <img :src="row[header.toLowerCase().replace(/ /g, '_')]" alt="Image" class="table-image" />
                        </template>
                        <template v-else>
                            {{ row[header.toLowerCase().replace(/ /g, '_')] }}
                        </template> -->
                        <template v-if="Array.isArray(row[header.toLowerCase().replace(/ /g, '_')])" class="">
                            <div v-for="file in row[header.toLowerCase().replace(/ /g, '_')]" :key="file" class="file-preview flex justify-center items-center">
                                <template v-if="isImage(file)">
                                    <img :src="file" alt="Image" class="table-image w-[300px] h-[200px]" />
                                </template>
                                <template v-else-if="isVideo(file)">
                                    <video :src="file" controls class="table-video w-[300px] h-[200px]"></video>
                                </template>
                            </div>
                        </template>
                        <template v-else>
                            <template v-if="isImage(row[header.toLowerCase().replace(/ /g, '_')])">
                                <img :src="row[header.toLowerCase().replace(/ /g, '_')]" alt="Image" class="table-image" />
                            </template>
                            <template v-else-if="isVideo(row[header.toLowerCase().replace(/ /g, '_')])">
                                <img :src="row[header.toLowerCase().replace(/ /g, '_')]" alt="Image" class="table-image" />
                            </template>
                            <template v-else class="">
                                {{ row[header.toLowerCase().replace(/ /g, '_')] }}
                            </template>
                        </template>
                    </td>
                    <td v-if="showView || showEdit || showDelete || showCopy || showUse || showArchive || showUnarchive || showBackup">
                        <button v-if="showUse && !row.is_used" @click="handleAction(row, 'use')" class="btn-use">
                            Use
                        </button>
                        <button v-if="showView" @click="handleAction(row, 'view')" class="btn-view">
                            View
                        </button>
                        <button v-if="showEdit" @click="handleAction(row, 'edit')" class="btn-edit">
                            Edit
                        </button>
                        <button v-if="showDelete" @click="handleAction(row, 'delete')" class="btn-delete">
                            Delete
                        </button>
                        <button v-if="showArchive" @click="handleAction(row, 'archive')" class="btn-archive">
                            Archive
                        </button>
                        <button v-if="showUnarchive" @click="handleAction(row, 'unarchive')" class="btn-unarchive">
                            Unarchive
                        </button>
                        <button v-if="showCopy" @click="handleAction(row, 'copy')" class="btn-copy">
                            Copy Password
                        </button>
                        <button v-if="showBackup" @click="handleAction(row, 'backup')" class="btn-copy">
                            Backup
                        </button>

                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages">
                Next
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ExtendedResponsiveTable',
    props: {
        headers: {
            type: Array,
            required: true,
        },
        rows: {
            type: Array,
            required: true,
        },
        rowsPerPage: {
            type: Number,
            default: 5,
        },
        showView: {
            type: Boolean,
            default: false,
        },
        showEdit: {
            type: Boolean,
            default: false,
        },
        showDelete: {
            type: Boolean,
            default: false,
        },
        showCopy: {
            type: Boolean,
            default: false,
        },
        showSearch: {
            type: Boolean,
            default: false,
        },
        showUse: {
            type: Boolean,
            default: false,
        },
        showArchive: {
            type: Boolean,
            default: false,
        },
        showUnarchive: {
            type: Boolean,
            default: false,
        },
        showBackup: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            searchQuery: '',
            currentPage: 1,
        };
    },
    computed: {
        filteredData() {
            if (this.searchQuery) {
                return this.rows.filter(row =>
                    this.headers.some(header => {
                        const key = header.toLowerCase().replace(/ /g, '_');
                        const value = row[key];
                        return value && value.toString().toLowerCase().includes(this.searchQuery.toLowerCase());
                    })
                );
            }
            return this.rows;
        },
        paginatedData() {
            const start = (this.currentPage - 1) * this.rowsPerPage;
            return this.filteredData.slice(start, start + this.rowsPerPage);
        },
        totalPages() {
            return Math.ceil(this.filteredData.length / this.rowsPerPage);
        },
    },
    methods: {
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        handleAction(row, action) {
            this.$emit('action-event', { action, row });
        },
        isImage(url) {
            if(!url) {
                return false;
            }

            return url.includes("http://res.cloudinary.com/dcmgsini6/image/upload/");
        },
        isVideo(url) {
            if(!url) {
                return false;
            }

            return url.includes("http://res.cloudinary.com/dcmgsini6/video/upload/");
        }
    },
};
</script>

<style scoped>
.responsive-table {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #2C3C4C;
}

th, td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #2C3C4C;
    color: white;
}

.search-input {
    width: 20%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:disabled {
    background-color: #ccc;
}

.pagination {
    margin-top: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

td {
    padding: 8px 12px;
    border-bottom: 1px solid #ddd;
    text-align: center; /* Ensure horizontal centering */
    vertical-align: middle; /* Ensure vertical centering */
}

.table-image {
    width: 200px;
    height: 100px;
    display: block; /* Ensures that the image is treated as a block element */
    margin-left: auto; /* Align image to the center horizontally */
    margin-right: auto;
    border-radius: 5px;
}


@media (max-width: 600px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }

    thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    tr {
        margin-bottom: 15px;
    }

    td {
        border: none;
        position: relative;
        padding-left: 50%;
        text-align: right;
    }

    td:before {
        position: absolute;
        top: 0;
        left: 0;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        content: attr(data-label);
        text-align: left;
        font-weight: bold;
    }
}

.btn-view {
    font-size: 12px;
    margin-right: 2px;
    background-color: #1ADCF7;
    color: black;
}

.btn-edit, .btn-copy, .btn-unarchive {
    font-size: 12px;
    margin-right: 2px;
    background-color: #33E88D;
    color: black;
}

.btn-delete {
    font-size: 12px;
    margin-right: 2px;
    background-color: #F59080;
    color: black;
}

.btn-use {
    font-size: 12px;
    margin-right: 2px;
    background-color: #1ADCF7;
    color: black;
}

.btn-archive {
    font-size: 12px;
    margin-right: 2px;
    background-color: #F59080;
    color: black;
}


</style>

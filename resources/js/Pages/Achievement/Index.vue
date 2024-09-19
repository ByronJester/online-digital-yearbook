<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    posts: {
        type: Array,
    },
});

// Form data for new post
const postContent = ref('');
const postImage = ref(null);
const postVideo = ref(null);

// File input refs to trigger from buttons
const imageInput = ref(null);
const videoInput = ref(null);
const createPost = async() => {
        const formData = new FormData();
        formData.append('content', postContent.value);
        formData.append('image', postImage.value);
        formData.append('value', postVideo.value);

        await Inertia.post(route('staff-aap-save-post'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: (page) => {
                // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
            },
            onError: (errors) => {
                // alert('There was an error uploading the file.');
            },
        });
    // }
};

// Function to handle liking a post
const toggleLike = async(post, status) => {

    const formData = new FormData();
    formData.append('post_id', post.id);
    formData.append('status', status);

    await Inertia.post(route('staff-aap-save-like'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        onSuccess: (page) => {
            // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
        },
        onError: (errors) => {
            // alert('There was an error uploading the file.');
        },
    });
};

// Function to toggle the comment input for a specific post
const toggleCommentInput = (post) => {
    post.showCommentInput = !post.showCommentInput;
};

// Function to add a comment
const addComment = async(post, commentText) => {
    const formData = new FormData();
    formData.append('post_id', post.id);
    formData.append('comment', commentText);

    await Inertia.post(route('staff-aap-save-comment'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        onSuccess: (page) => {
            // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
        },
        onError: (errors) => {
            // alert('There was an error uploading the file.');
        },
    });
};
</script>

<template>
    <Head title="Achievements and Recognitions" />

    <AuthenticatedLayout>
        <!-- Create new post form -->
        <div class="mb-10 border-2 border-black rounded-md p-5">
            <h2 class="text-xl font-bold mb-2">New Post</h2>
            <textarea v-model="postContent" placeholder="What's on your mind?" class="w-full p-2 border mb-4 rounded-lg" rows="5"></textarea>

            <!-- Upload Buttons with Hidden Inputs -->
            <div class="flex gap-4 mb-4">
                <button @click="imageInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Photo</button>
                <button @click="videoInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Video</button>

                <input ref="imageInput" type="file" @change="(e) => postImage = e.target.files[0]" accept="image/*" class="hidden" />
                <input ref="videoInput" type="file" @change="(e) => postVideo = e.target.files[0]" accept="video/*" class="hidden" />
            </div>

            <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Post</button>
        </div>

        <!-- News feed posts -->
        <div class="space-y-6">
            <div v-for="post in posts" :key="post.id" class="border rounded-lg p-4 bg-white">
                <div class="font-bold">{{ post.user.fullname }}</div>
                <p>{{ post.content }}</p>

                <!-- Display image or video if available -->

                <div v-if="post.image" class="my-4">
                    <img :src="post.image" alt="Post Image" class="w-full h-[200px]">
                </div>
                <div v-if="post.video" class="my-4">
                    <video controls class="w-full h-[200px]">
                        <source :src="post.video" type="video/mp4" />
                    </video>
                </div>

                <!-- Likes and comments -->
                <div class="flex justify-between items-center mt-4">
                    <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                        ? 'Unlike'
                        : 'Like' )"
                        class="text-blue-500"
                    >
                        {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                            ? 'Unlike'
                            : 'Like'
                        }}
                        <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                        <!-- {{ post.likes }} -->
                    </button>
                    <button @click="toggleCommentInput(post)" class="text-blue-500">
                        <i class="fa fa-comment"></i> {{ post.comments.length }}
                    </button>
                </div>

                <!-- Comment input, displayed above other comments -->
                <div v-if="post.showCommentInput" class="mt-4">
                    <input type="text" placeholder="Add a comment..."
                           @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''"
                           class="border p-1 rounded w-full mb-2">
                </div>

                <!-- Display comments -->
                <div v-if="post.comments.length > 0 " class="mt-4 ml-4">
                    <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                        <p class="font-bold">{{ comment.commentor }}</p>
                        <p class="ml-3">{{ comment.comment }}</p>
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
</style>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';

defineProps({
    posts: {
        type: Array,
    },
});

// Form data for new post
const postContent = ref('');
const postImage = ref(null);
const postVideo = ref(null);
const seeComment = ref(false)

// File input refs to trigger from buttons
const imageInput = ref(null);
const videoInput = ref(null);

const loading = ref(false)

const createPost = () => {
    swal({
        title: "Are you sure to save this achievement?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            const formData = new FormData();
            formData.append('content', postContent.value);
            formData.append('image', postImage.value);
            formData.append('video', postVideo.value);

            setTimeout(() => {
                Inertia.post(route('staff-aap-save-post'), formData, {
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
        }
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

const textComment = ref(null);
const commentId = ref(null);

// Function to add a comment
const addComment = async(post, commentText) => {
    if(!commentId.value) {
        const formData = new FormData();
        formData.append('post_id', post.id);
        formData.append('comment', commentText);

        await Inertia.post(route('staff-aap-save-comment'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: (page) => {
                // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                textComment.value = null
                commentId.value = null
            },
            onError: (errors) => {
                // alert('There was an error uploading the file.');
            },
        });
    } else {
        const formData = new FormData();
        formData.append('id', commentId.value);
        formData.append('comment', textComment.value);

        await Inertia.post(route('staff-aap-edit-comment'), formData, {

            onSuccess: (page) => {
                // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                textComment.value = null
                commentId.value = null
            },
            onError: (errors) => {
                // alert('There was an error uploading the file.');
            },
        });
    }

};

const deletePost = (id) => {
    swal({
        title: "Are you sure to delete this achievement post?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            const formData = new FormData();
            formData.append('id', id);

            Inertia.post(route('delete-achievement'), formData, {
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
        }
    });
}

const mediaSrc = ref(null);
const mediaType = ref(null);

const openMedia = (src, type) => {
    mediaSrc.value = src
    mediaType.value = type

    var modal = document.getElementById("defaultModal");
    modal.style.display = "block";
}

const closeModal = () => {
    mediaSrc.value = null
    mediaType.value = null

    var modal = document.getElementById("defaultModal");
    modal.style.display = "none";
}

const deleteComment = (id) => {
    swal({
        title: "Are you sure to delete this comment?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            const formData = new FormData();
            formData.append('id', id);

            Inertia.post(route('staff-aap-delete-comment'), formData, {
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
        }
    });
}

</script>

<template>
    <Head title="Achievements and Recognitions" />

    <AuthenticatedLayout>
        <!-- Create new post form -->

        <div class="mb-10 border-2 border-black rounded-md p-5" v-if="$page.props.auth.user.user_type == 'school_staff'">
            <div class="w-full flex justify-center items-center" v-if="loading">
                <VueSpinner size="50" color="red" />
             </div>

             <div class="w-full" v-else>
                <h2 class="text-xl font-bold mb-2">New Post</h2>
                <textarea v-model="postContent" placeholder="What's on your mind?" class="w-full p-2 border mb-4 rounded-lg" rows="5"></textarea>

                <!-- Upload Buttons with Hidden Inputs -->
                <div class="flex gap-4 mb-4">
                    <div class="w-full">
                        <button @click="imageInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded mr-1">Upload Photo</button>
                        <button @click="videoInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Video</button>

                        <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Post</button>
                    </div>



                    <input ref="imageInput" type="file" @change="(e) => postImage = e.target.files[0]" accept="image/*" class="hidden" />
                    <input ref="videoInput" type="file" @change="(e) => postVideo = e.target.files[0]" accept="video/*" class="hidden" />
                </div>

                <!-- <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Post</button> -->
             </div>

        </div>

        <div
            id="defaultModal"
            tabindex="-1"
            aria-hidden="true"
            style="background-color: rgba(0, 0, 0, 0.7)"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
        >
            <div class="h-screen flex justify-center items-center">
                <div class="relative w-[50%] h-[500px] max-w-2xl max-h-full">



                    <div class="relative bg-white rounded-lg shadow" v-if="mediaType == 'image'">
                        <span class="text-xl float-right mr-2" @click="closeModal()">
                            <i class="fa-solid fa-xmark cursor-pointer"></i>
                        </span>
                        <img :src="mediaSrc" class="w-full h-[500px]"/>
                    </div>

                    <div class="relative bg-white rounded-lg shadow" v-else>

                        <video controls class="w-full h-full cursor-pointer">
                            <source :src="mediaSrc" type="video/mp4" />
                        </video>

                    </div>
                </div>
            </div>
        </div>

        <!-- News feed posts -->
        <div class="space-y-6">
            <div v-for="post in posts" :key="post.id" class="border rounded-lg p-4 bg-white h-full">
                <div class="font-bold">
                    <span>
                        {{ post.user.fullname }}
                    </span>
                    <span class="float-right text-xs text-red-500 cursor-pointer" v-if="$page.props.auth.user.user_type == 'school_staff'"
                        @click="deletePost(post.id)"
                    >
                        Delete
                    </span>
                </div>
                <p class="text-xs">{{ post.created_at }}</p>

                <p class="mt-8">{{ post.content }}</p>

                <!-- Display image or video if available -->

                <div v-if="post.image" class="my-4 flex justify-center items-center">
                    <img :src="post.image" alt="Post Image" class="w-[500px] h-[300px]" @click="openMedia(post.image, 'image')">
                </div>
                <div v-if="post.video" class="my-4 flex justify-center items-center">
                    <video controls class="w-[500px] h-[250px]">
                        <source :src="post.video" type="video/mp4" />
                    </video>
                </div>

                <!-- Likes and comments -->
                <div class="flex justify-between items-center mt-4" v-if="$page.props.auth.user.user_type == 'school_staff'">
                    <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                        ? 'Unlike'
                        : 'Like' )"
                        :class="{'text-blue-500': post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0}"
                    >
                        <!-- {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                            ? 'Unlike'
                            : 'Like'
                        }} -->
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
                           class="border p-1 rounded w-full mb-2"
                           v-model="textComment"
                           >
                </div>

                <!-- Display comments -->
                <div v-if="post.comments.length > 0 && post.showCommentInput  " class="mt-4 ml-4">
                    <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                        <p class="font-bold">{{ comment.commentor }}</p>
                        <p class="ml-3">
                            <span>{{ comment.comment }}</span>

                            <span class="float-right text-red-500 text-xs" v-if="$page.props.auth.user.id == comment.user_id ">
                                <i class="fa-solid fa-trash ml-2 cursor-pointer" @click="deleteComment(comment.id)">
                                </i>
                            </span>
                            <span class="float-right text-green-500 text-xs" v-if="$page.props.auth.user.id == comment.user_id ">
                                <i class="fa-solid fa-pen-to-square cursor-pointer" @click="textComment = comment.comment; commentId = comment.id">

                                </i>
                            </span>
                        </p>
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

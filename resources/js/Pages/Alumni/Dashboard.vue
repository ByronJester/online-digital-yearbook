<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, inject, watchEffect } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    data: {
        type: Array,
    },
    notifications: {
        type: Array
    },
    stories: {
        type: Array
    }
});
const carouselRef = ref(null);
let intervalId = null;

const startAutoplay = () => {
  if (carouselRef.value) {
    intervalId = setInterval(() => {
      if (carouselRef.value.next) {
        carouselRef.value.next();
      }
    }, 3000); // 3000ms = 3 seconds
  }
};

const stopAutoplay = () => {
  if (intervalId) {
    clearInterval(intervalId);
  }
};

onMounted(() => {
  startAutoplay();
});

onUnmounted(() => {
  stopAutoplay();
});

const toggleLike = async (post, status) => {
    if(post.type == 'album') {
        const formData = new FormData();
        formData.append('post_id', post.id);
        formData.append('status', status);
        formData.append('user_id', post.user_id);


        await Inertia.post(route('staff-album-save-like'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
    } else {
        console.log('a')
        console.log(post)
        const formData = new FormData();

        formData.append('post_id', post.id);
        formData.append('status', status);
        formData.append('user_id', post.user_id);

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
    }

};

// Function to toggle the comment input for a specific post
const toggleCommentInput = (post) => {
    post.showCommentInput = !post.showCommentInput;
};

const textComment = ref(null);
const commentId = ref(null);

const formComment = useForm({
    post_id: '',
    comment: '',
});

// Function to add a comment
const addComment = async (post, commentText) => {
    if(post.type == 'album') {
        if(!commentId.value) {
            formComment.post_id = post.id
            formComment.comment = commentText
            // const formData = new FormData();
            // formData.append('post_id', post.id);
            // formData.append('comment', commentText);
            // formData.append('user_id', post.user_id);

            // await Inertia.post(route('staff-album-save-comment'), formData, {
            //     headers: {
            //         'Content-Type': 'multipart/form-data',
            //     },
            // });

            // textComment.value = null
            // commentId.value = null

            await formComment.post(route('staff-album-save-comment'), {
                onSuccess: (page) => {
                    location.reload()
                },
                onError: (errors) => {
                    formComment.post_id = null
                    formComment.comment = null
                    alert('You commented a bad word');
                }
            });
        } else {

            const formData = new FormData();
            formData.append('id', commentId.value);
            formData.append('comment', textComment.value);

            await Inertia.post(route('staff-album-edit-comment'), formData, {

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

    } else {
        if(!commentId.value) {
            // const formData = new FormData();
            // formData.append('post_id', post.id);
            // formData.append('comment', commentText);

            // await Inertia.post(route('staff-aap-save-comment'), formData, {
            //     headers: {
            //         'Content-Type': 'multipart/form-data',
            //     },
            //     onSuccess: (page) => {
            //         // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
            //         textComment.value = null
            //         commentId.value = null
            //     },
            //     onError: (errors) => {
            //         formComment.post_id = null
            //         formComment.comment = null
            //         alert('You commented a bad word');
            //         // alert('There was an error uploading the file.');
            //     },
            // });
            formComment.post_id = post.id
            formComment.comment = commentText

            await formComment.post(route('staff-aap-save-comment'), {
                onSuccess: (page) => {
                    location.reload()
                },
                onError: (errors) => {
                    formComment.post_id = null
                    formComment.comment = null
                    alert('You commented a bad word');
                }
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

    }
};

const sharePost = (post) => {

    console.log(post)
    swal({
        title: "Are you sure to repost this feed ?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            const formData = new FormData();
            formData.append('post_id', post.id);
            formData.append('post_type', post.type);
            formData.append('user_id', post.user_id);

            Inertia.post(route('alumni-share-feed'), formData, {
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

const deleteComment = (id, type) => {
    swal({
        title: "Are you sure to delete this comment?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            if(type == 'album') {
                const formData = new FormData();
                formData.append('id', id);

                Inertia.post(route('staff-album-delete-comment'), formData, {
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
            } else {
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

        }
    });
}

const postData = ref([])
postData.value = props.data

const search = (event) => {
    let search = event.target.value

    if(search == '') {
        postData.value = props.data
    } else {
        postData.value = props.data.filter(x => { return x.content.toLowerCase().includes(search.toLowerCase()) || x.user.fullname.toLowerCase().includes(search.toLowerCase())});
    }
}

const viewProfile = (id) => {
    Inertia.get(route('profile.view', id))
}

const notificationModalVisible = ref(false);

const openNotificationModal = () => {
    notificationModalVisible.value = true;
};

const closeNotificationModal = () => {
    notificationModalVisible.value = false;
};

const openNotification = (n) => {
    if(n.type =='album') {
        Inertia.get(route('album.view', n.redirect_id))
    } else {
        Inertia.get(route('achievement.view', n.redirect_id))
    }
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

// const searchQuery = inject('searchQuery', ref(''));

// if (!searchQuery) {
//     console.warn("searchQuery injection not found");
// }

// console.log(searchQuery)

// watchEffect(() => {
//   console.log('Search Query:', searchQuery.value); // Debug the injected value
// });


</script>

<script>
     // If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
    import 'vue3-carousel/dist/carousel.css'
    import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

    export default {
        name: 'App',
        components: {
            Carousel,
            Slide,
            Pagination,
            Navigation,
        },
    }
</script>

<template >
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="w-full p-5">
            <div class="w-full flex flex-col">

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

                <div class="w-full">
                    <div class="w-full border rounded-lg p-4 bg-white h-auto mt-2" v-for="(post, key) in postData" :key="post.id">
                        <div class="space-y-6" v-if="post.type == 'achievement'">
                            <div class="w-full">
                                <div class="font-bold cursor-pointer" @click="viewProfile(post.user.id)">
                                    {{ post.user.fullname }}
                                </div>
                                <p class="text-xs mt-2 mb-5">{{ post.created_at }}</p>

                                <p>{{ post.content }}</p>

                                <!-- Display image or video if available -->

                                <div v-if="post.image" class="my-4 flex justify-center items-center">
                                    <img :src="post.image" alt="Post Image" class="w-[400px] h-[300px] cursor-pointer" @click="openMedia(post.image, 'image')">
                                </div>
                                <div v-if="post.video" class="my-4 flex justify-center items-center">
                                    <video controls class="w-[400px] h-[300px]">
                                        <source :src="post.video" type="video/mp4" />
                                    </video>
                                </div>

                                <!-- Likes and comments -->
                                <div class="w-full mt-20">
                                    <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' )" :class="{'text-blue-500': post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0}">
                                        <!-- {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' }} -->
                                        <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                                    </button>

                                    <button class="text-green-500 float-right ml-2" @click="sharePost(post)" v-if="$page.props.auth.user.user_type == 'school_alumni'">
                                        <i class="fa fa-share"></i> {{ post[key].shared_count }}
                                    </button>
                                    <button @click="toggleCommentInput(post)" class="text-blue-500 float-right">
                                        <i class="fa fa-comment"></i> {{ post.comments.length }}
                                    </button>
                                </div>

                                <!-- Comment input, displayed above other comments -->
                                <div v-if="post.showCommentInput" class="mt-4">
                                    <input type="text" placeholder="Add a comment..."
                                        @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''"
                                        v-model="textComment"
                                        class="border p-1 rounded w-full mb-2">
                                </div>

                                <!-- Display comments -->
                                <div v-if="post.comments.length > 0 && post.showCommentInput  " class="mt-4 ml-4">
                                    <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                                        <p class="font-bold">{{ comment.commentor }}</p>
                                        <p class="ml-3">
                                            <span>{{ comment.comment }}</span>

                                            <span class="float-right text-red-500 text-xs" v-if="$page.props.auth.user.id == comment.user_id ">
                                                <i class="fa-solid fa-trash ml-2 cursor-pointer" @click="deleteComment(comment.id, post.type)">
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

                        <div class="w-full" v-else>
                            <div class="font-bold cursor-pointer" @click="viewProfile(post.user.id)">
                                {{ post.user.fullname }}
                            </div>
                            <p class="text-xs mt-1 mb-10">{{ post.created_at }}</p>

                            <div class="w-full text-center border border-black rounded-md mt-3">
                                <p class="text-2xl p-3">{{ post.content }}</p>
                            </div>

                            <div class="w-full text-center border border-black rounded-md mt-3" v-if="post.description">
                                <p class="text-md p-3">{{ post.description }}</p>
                            </div>

                            <!-- Display multiple images if available -->
                            <div v-if="post.image.length > 0" class="my-4">
                                <div class="w-full carousel-container">
                                    <carousel :items-to-show="2" ref="carouselRef">
                                        <slide v-for="i in post.image" :key="i" >
                                            <img :src="i" class="w-full h-[300px] mr-1 cursor-pointer" @click="openMedia(i, 'image')"/>
                                        </slide>

                                        <template #addons>
                                            <!-- <navigation /> -->
                                            <pagination />
                                        </template>
                                    </carousel>
                                </div>
                            </div>

                            <!-- Display multiple videos if available -->
                            <div v-if="post.video.length > 0" class="my-4">
                                <div class="w-full carousel-container">
                                    <carousel :items-to-show="2" ref="carouselRef">
                                        <slide v-for="i in post.video" :key="i">
                                            <!-- <img :src="i" class="w-full h-[200px] mr-1"/> -->
                                            <video controls class="w-full h-[250px]">
                                                <source :src="i" type="video" />
                                            </video>
                                        </slide>

                                        <template #addons>
                                            <!-- <navigation /> -->
                                            <pagination />
                                        </template>
                                    </carousel>
                                </div>
                            </div>

                            <!-- Likes and comments -->
                            <div class="w-full mt-20">
                                <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' )" :class="{'text-blue-500': post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0}">
                                    <!-- {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' }} -->
                                    <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                                </button>

                                <button class="text-green-500 float-right ml-2" @click="sharePost(post)" v-if="$page.props.auth.user.user_type == 'school_alumni'">
                                    <i class="fa fa-share"></i> {{ post[key].shared_count }}
                                </button>
                                <button @click="toggleCommentInput(post)" class="text-blue-500 float-right">
                                    <i class="fa fa-comment"></i> {{ post.comments.length }}
                                </button>
                            </div>

                            <!-- Comment input, displayed above other comments -->
                            <div v-if="post.showCommentInput" class="mt-4">
                                <input type="text" placeholder="Add a comment..." @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''" class="border p-1 rounded w-full mb-2"
                                    v-model="textComment"
                                >
                            </div>

                            <!-- Display comments -->
                            <div v-if="post.comments.length > 0 && post.showCommentInput" class="mt-4 ml-4">
                                <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                                    <p class="font-bold">{{ comment.commentor }}</p>
                                    <p class="ml-3">
                                        <span>{{ comment.comment }}</span>

                                        <span class="float-right text-red-500 text-xs" v-if="$page.props.auth.user.id == comment.user_id ">
                                            <i class="fa-solid fa-trash ml-2 cursor-pointer" @click="deleteComment(comment.id, post.type)">
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
                </div>

                <!-- <div class="w-[25%] h-[80%] border rounded-md bg-white border-black fixed right-2 overflow-y-scroll">
                    <div class="flex flex-col p-2">
                        <div class="w-full mt-5" v-for="story in stories" :key="story.id">
                            <p class="font-bold text-md text-center">
                                {{ story.student_name }}
                            </p>
                            <p class="text-xs text-justify mt-2">
                                {{ story.content }}
                            </p>

                            <img :src="story.file" class="w-full h-[250px] mt-3"/>
                        </div>
                    </div>
                </div> -->
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.student {
    border-radius: 10px;
    border: 1px solid black;
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

.notification-modal {
    position: absolute;
    right: 60px;
    top: 150px;
    width: 350px;
}
</style>

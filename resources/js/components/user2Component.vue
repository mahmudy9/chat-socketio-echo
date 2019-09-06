<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm a user2 component.
                        <hr>
                        <br>
                        <ul >
                            <li  v-for="m in messages">{{m.m}}</li>
                        </ul>

                        <form v-on:submit.prevent="submit">
                            <input type="text" v-model="moody" />
                            <button type="submit">Send</button>
                        </form>
                         <hr>
                        <br>
                        <router-link to="/testsocket">back</router-link>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                messages: [ ],
                moody: ''
            }
        },
        methods: {
            submit() {
                axios.post('/api/sendevent' , {
                    message: this.moody
                }).then(function(res) {
                    //console.log(res);
                }).catch(function(err){
                    console.error(err);
                });
            }
        },
        mounted() {
                Echo.channel('my-channel')
                    .listen('.chat.event' , (e) => {
                        //console.log(e);
                        this.messages.push({
                            m: e.message
                        });
                        this.moody = "";
                });

        }
    }
</script>
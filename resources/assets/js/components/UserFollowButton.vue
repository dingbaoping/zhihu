<template>
    <div>
        <button class="btn btn-default" :class="{btn-primary:followed}" v-text="text" @click="follow"></button>
    </div>   
</template>

<script>
    export default {
        props:['user'],
        data(){
            return {
                followed:false
            }
        },
        mounted() {
            this.$http.get('/api/user/followers/'+this.user}).then(res=>{
                console.log(res.data);
                this.followed = res.data.followed;
            })
        },
        computed:{
            text(){
                return this.followed ? '已关注' : '关注他';
            }
        },
        methods:{
            follow(){
                this.$http.post('/api/user/follow'+this.user}).then(res=>{
                    console.log(res.data);
                    this.followed = res.data.followed;
                })
            }
        }
    }
</script>

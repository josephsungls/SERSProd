<template>
    <div class = "container">
        <h1>{{course_info.Course_Name}}</h1>
        <h4>{{course_info.Run_Desc}}</h4>
        <h4>Class: {{course_info.Class}}</h4>
        <h4>Course Date: {{course_info.Run_StartDate}}</h4>

        <form class ="mb-5">
            <div class="form-group" v-for="(question, q_no , idx) in feedbackQuestions" :key="idx">
                <span v-if="idx>0">
                    <label :for="q_no">{{q_no}} - {{question}}</label>

                    <br/>

                    <div class="form-check" v-if="question.includes('(1 is Lowest and 5 is Highest)')">

                        <!-- <input type="range" class="form-range" min="0" max="5" :id="q_no" v-model="feedbackAnswers[idx]" >
                        <br/>
                        <b>You rated:</b> <label :for="q_no" class="form-label" >{{feedbackAnswers[idx]}}</label> -->
                        <input class="form-check-input" type="radio" :name="q_no" :id="q_no + '1'" value="1" v-model="feedbackAnswers[idx]">
                        <label class="form-check-label" :for="q_no + '1'">1</label>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <input class="form-check-input" type="radio" :name="q_no" :id="q_no + '2'" value="2" v-model="feedbackAnswers[idx]">
                        <label class="form-check-label" :for="q_no + '2'">2</label>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <input class="form-check-input" type="radio" :name="q_no" :id="q_no + '3'" value="3" v-model="feedbackAnswers[idx]">
                        <label class="form-check-label" :for="q_no + '3'">3</label>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <input class="form-check-input" type="radio" :name="q_no" :id="q_no + '4'" value="4" v-model="feedbackAnswers[idx]">
                        <label class="form-check-label" :for="q_no + '4'">4</label>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <input class="form-check-input" type="radio" :name="q_no" :id="q_no + '5'" value="5" v-model="feedbackAnswers[idx]">
                        <label class="form-check-label" :for="q_no + '5'">5</label>
                    </div>

                    <textarea class="form-control" id="q_no" rows="3" v-else v-model="feedbackAnswers[idx]"></textarea>
                </span>
            </div>
            <div class="text-center">
                <button type="button" class ="btn btn-primary submit-feedback" @click='submitFeedback'>Submit Feedback</button>
            </div>
        </form >
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../router.js'
    import {RUNINFORMATION_URL, FEEDBACKQUESTION_URL, SUBMITFEEDBACK_URL} from '../variables'
    export default {
        name: 'Feedback',
        data(){
            return {
                course_id: this.$route.params.course_id, 
                run_start: this.$route.params.course_startdate,
                class: this.$route.params.class,
                form_id: this.$route.params.cf_id,
                course_info: [],
                feedbackQuestions: [],
                feedbackAnswers: []
            }
        },
        methods:{
            getRunInformation(){
                    axios.get(RUNINFORMATION_URL,
                    {
                        params: {
                            'course_id': this.course_id,
                            'run_start': this.run_start,
                            'class': this.class
                        },
                        withCredentials: true
                    }
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        this.course_info = response.data[0]
                    })
            },
            getFeedbackQuestions(){
                    axios.get(FEEDBACKQUESTION_URL,
                    {
                        params: {
                            'form_id': this.form_id
                        },
                        withCredentials: true
                    }
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        const obj_val = Object.values(response.data[0])
                        this.feedbackQuestions = response.data[0]
                        this.feedbackAnswers[0] = this.form_id
                        for (var i = 1; i < obj_val.length; i++){
                            if (obj_val[i].includes('(1 is Lowest and 5 is Highest)')){
                                this.feedbackAnswers[i] = 3
                            }else{
                                this.feedbackAnswers[i] = null
                            }
                        }
                    })
            },
            submitFeedback(){
                axios.post(SUBMITFEEDBACK_URL,{
                    'course_id': this.course_id,
                    'run_start': this.run_start,
                    'class': this.class,
                    'feedback': this.feedbackAnswers,
                }).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    console.log(response)
                    Swal.fire({
                        icon: 'success',
                        title: 'We thank you for your feedback!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result)=>{
                        if (result.dismiss === Swal.DismissReason.timer) {
                            router.push('/')
                        }
                    })
                    
                })
            }
        },
        mounted(){
            this.getRunInformation()
            this.getFeedbackQuestions()
        }
    }
</script>
<style scoped>
    .submit-feedback{
        border-radius: 20px;
        width: 150px;
        font-family: var(--body-text);
        font-weight: bold;
    }
    .form-range{
        width:80%;
        height:20px;
    }
</style>
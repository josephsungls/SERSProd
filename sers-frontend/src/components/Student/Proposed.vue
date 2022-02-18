<template>
    <div class = "container-fluid display-container">

        <div class="table-container">
            <div class="table-header">
                <h4>Proposed Courses</h4>
                <p class = "text-muted">Course not offered? <span data-toggle="modal" data-target="#proposeCourse" class='propose'> <i class="fas fa-pen-square"></i> Propose it here!</span></p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">Class Description</th>
                        <th scope = "col">Status</th>
                    </tr>
                </thead>
                <tbody  v-if="proposed.length != 0" >
                    
                    <tr v-for="course in proposed" :key="course" @click="changeStatus(course)" data-toggle="modal" data-target="#moreInfo">
                        <td><b>{{course.CP_Name}}</b></td>
                        <td>{{course.CP_Desc}}</td>
                        <td>
                            <span :class="['badge', 'badge-pill' , course.CP_Status]">

                                <span>{{course.CP_Status}}</span>

                            </span>
                        </td>

                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan='3' class="pt-5 text-center" >
                            <h4 style="font-family: 'Mulish', sans-serif;">No courses at the moment</h4>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!--More info Modal -->
        <div class="modal fade" id="moreInfo" tabindex="-1" role="dialog" aria-labelledby="moreInfo" aria-hidden="true" data-backdrop="false"  v-if="proposed.length != 0" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.CP_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{current.CP_Desc}}</p>
                        <!-- <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.CP_Status == 'reviewing'" @click="removeProposal(current)">Remove</button> -->
                    </div>
                </div> 
            </div>
        </div>

        <!--Course Proposal Modal -->
        <div class="modal fade" id="proposeCourse" tabindex="-1" role="dialog" aria-labelledby="proposeCourse" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Propose a course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form v-if="$store.state.authenticated">
                        <div class="form-group">

                            <label for="name">Course Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Name" v-model="course.course_name">

                            <label for="course-desc">Course Description</label>
                            <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️"  v-model="course.course_desc"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" @click="submit()" data-dismiss="modal" aria-label="Close">Submit</button>
                        </form>
                        
                        <p v-else>
                            Please log in <router-link to="/login">here</router-link> to propose a course
                        </p>
                    
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
// INDICATEDINTEREST_URL
// ONGOINGCAMPAIGN_URL
    import axios from 'axios'
    import {STUDENTPROPOSALS_URL, PROPOSECOURSE_URL} from '../../variables'
    export default {
        name: "Proposed",
        data(){
            return {
                proposed: [],
                current: {},
                course:{
                    course_name: '',
                    course_desc: ''
                }
            }
        },
        methods:{
           getProposedCourses(){
                    axios.get(STUDENTPROPOSALS_URL,
                    {
                        withCredentials: true
                    }
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        console.log(response.data)
                        this.proposed = response.data
                        
                    })

            },
            changeStatus(course){
                this.current = course
            },
            submit(){
                axios.post(PROPOSECOURSE_URL,
                {
                    'course': this.course,
                    'user_id': this.$store.state.user_id
                }
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    console.log(response)
                    
                    if (response.data != 'created'){
                        console.log(response)
                    }else{
                        this.getProposedCourses()
                    }

                })
            }
        },
        mounted(){
            this.getProposedCourses()
            
        }
    }
</script>
<style scoped>

    .badge-pill{
        padding: 10px;
        border-radius: 50px;
        font-size: 13.5px;
    }

    .badge-pill::after{
        box-shadow: 2px;
    }

    :deep(.approved){
        /* background-color: var(--pill-green); */
        background-color: #29CC97;
        color: white;
    }

    :deep(.reviewing){
        /* background-color: var(--pill-yellow); */
        background-color: #FFF380;
        color: black;
    }

    :deep(.rejected){
        /* background-color: var(--gray); */
        background-color: #C21E56;
        color: white;
    }


    .propose{
        display: inline-block;
        color: var(--button-color);
        cursor:pointer;
        transition:0.2s;
        
    }


    thead{
        top:0;
        position:sticky;
        /* border-radius: 10px 50px 10px 10px; */
        /* background:var(--navigation-color); */
        background-color: rgb(245, 242, 231);
        color: black;
        word-wrap:break-word;
        
        /* border:none; */
        /* box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.2); */
    }

    td {
        transition: 0.1s;
        vertical-align: middle;
    }

    td,th{
        border:none;
    }

    tbody tr:hover {
        background-color: rgba(78, 47, 49, 0.767);
        font-size: 16.5px;
    }


</style>
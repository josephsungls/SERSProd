<template>
    <div class = "container-fluid display-container">

        <div class="table-container">
            <div class="table-header">
                <h4>Your Proposals</h4>
                <p class = "text-muted">Courses which you have proposed</p>
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
                    
                    <tr v-for="course in proposed" :key="course" @click="changeStatus(course)" data-toggle="modal" data-target="#exampleModalCenterTitle">
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
                        <div class="table-header"><h4>No courses proposed</h4></div>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenterTitle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false"  v-if="proposed.length != 0" >
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
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.CP_Status == 'reviewing'" @click="removeProposal(current)">Remove</button>
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
    import {USERPROPOSED_URL, REMOVEPROPOSAL_URL} from '../../../variables'
    export default {
        name: "Proposed",
        data(){
            return {
                proposed: [],
                
                current: {}
            }
        },
        methods:{
           getProposedCourses(){
                    axios.get(USERPROPOSED_URL,
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
            removeProposal(course){
                axios.post(REMOVEPROPOSAL_URL,{
                    'course_id': course.CP_ID,


                }).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    console.log(response)
                    this.getProposedCourses()

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
        width: 100px;
    }
    :deep(.reviewing){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.approved){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.rejected){
        background-color: var(--pill-red);
        color: white;
    }

</style>
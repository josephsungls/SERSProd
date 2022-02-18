<template>
    <div class = "container-fluid display-container">
        <span class='back'>
            <button type='button' @click="backToCourse()"><i class="fas fa-long-arrow-alt-left"></i></button>
        </span>
        <div class="table-container">
            <div class="table-header">
                <h4>Course History</h4>
                <p class="text-muted">Runs conducted for the course.</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Instructors</th>
                        <th scope = "col">Venue</th>
                        <th scope = "col">Date</th>
                        <th scope = "col">Slots Taken</th>
                        <th scope = "col">Feedback Submitted</th>
                        <th scope = "col">Status</th>
                        <!-- <th scope = "col" class = "status text-center">Course Status</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="runs.length==0">
                        <td colspan="6">
                            <h4>No runs for this course.</h4>
                        </td>
                    </tr>
                    <tr v-for="run in runs" :key="run" @click="selectRun(run)">
                        <!-- {{course}} -->
                        <td>
                            <b>
                                {{run.RunCourse_ID}} 

                            </b> 
                            <br> 
                            {{run.Course_Name}} - Class:{{run.Class}}
                        </td>
                        <td><br>{{processInstructors(run.User_Name)}}</td>
                        <td><br> {{run.Run_Venue}}</td>
                        <td><br> {{run.Run_StartDate}}, {{run.Run_StartTime}}</td>
                        <td><br> {{run.No_Registrations}}/{{run.Run_MaxSlots}}</td>
                        <td><br> 
                            <span v-if ="run.Feedback_Count">
                                {{run.Feedback_Count}}
                            </span>
                            <span v-else>0</span>
                            /{{run.Run_MaxSlots}}</td>
                        <td><br> 
                            <span :class="['badge', 'badge-pill', run.Run_Status, 'more']"  @click="changeCurrent(course)">
                                {{run.Run_Status}}
                            </span>
                        </td>

                    </tr>
                </tbody>
            </table>

        </div>
        
    </div>
</template>
<script>
    export default{
        data(){
            return{

            }            
        },
        props:{
            runs: Array
        },
        methods:{
            selectRun(course){
                this.$parent.selectRun(course)
            },
            backToCourse(){
                this.$parent.backToCourse()
            },
            processInstructors(instructors){
                if(instructors){
                return instructors.split(',').join(', ')
                }
            }
        }
    }
</script>
<style scoped>
    :deep(.ended){
        background-color: var(--pill-red);
        color: white;
    }
    :deep(.offered){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.updated){
        background-color: var(--pill-yellow);
        color: white;
    }
</style>
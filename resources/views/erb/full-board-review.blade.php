@section('title', 'Assign Full Board Review')
<x-erb-layout>
    <!-- Main Content -->
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-md:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            FULL BOARD REVIEW
        </h2>
        <br>

        <!-- CSS NG SEARCH BAR -->
        <div class="top-controls">
            <div class="search-wrapper mt-1 flex max-sm:justify-center max-sm:items-center"></div>
        </div>

        <table id="myTable" class="display overflow-scroll border-collapse w-full">
            <thead class="bg-primary text-white text-lg/7 max-lg:text-base/7">
                <tr class="header-table">
                    <th class="w-[16.66%]">Research Protocol</th>
                    <th class="w-[16.66%]">Reviewer(s)</th>
                    <th class="w-[16.66%]">P.I. Name</th>
                    <th class="w-[16.66%]">Co-I. Name(s)</th>
                    <th class="w-[16.66%]">Research Title</th>
                    <th class="w-[16.66%]">Date Assigned</th>
                </tr>
            </thead>
            <tbody class="text-base/7 max-lg:text-sm/6">
                <td>ERB-2025-001</td>
                <td>Cardo Dalisay, Juan Dela Cruz</td>
                <td>John Doe</td>
                <td>Patrick Starr</td>
                <td>MCU-RRS</td>
                <td>10/21/2025<br>23:20:22</td>
            </tbody>
        </table>
        <div class="grid grid-cols-2 max-sm:block gap-x-5">
            <div class="max-sm:max-w-full">
                <div class="max-md:mt-3 bg-lightgray p-3 font-semibold max-sm:text-sm shadow-md rounded-md">
                    <h3 class="text-lg font-semibold max-md:text-base mb-3">Assigning Reviewers</h3>
                    <div class="gap-x-3 gap-y-3 grid grid-cols-2">
                        <div class="room cursor-pointer bg-gray hover:bg-darkgray px-3 py-2 rounded-md"
                            data-room="John Doe">
                            John Doe
                        </div>
                        <div class="room cursor-pointer bg-gray hover:bg-darkgray px-3 py-2 rounded-md"
                            data-room="Alfreds Futterkiste">
                            Alfreds Futterkiste
                        </div>
                        <div class="room cursor-pointer bg-gray hover:bg-darkgray px-3 py-2 rounded-md"
                            data-room="Juan Dela Cruz">
                            Juan Dela Cruz
                        </div>
                        <div class="room cursor-pointer bg-gray hover:bg-darkgray px-3 py-2 rounded-md"
                            data-room="Cardo Dalisay">
                            Cardo Dalisay
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-md:mt-3 bg-lightgray shadow-md rounded-md p-3">
                <h3 class="text-lg font-semibold max-md:text-base mb-3">Assigned Reviewers</h3>
                <ul id="assignedList" class="list-disc px-6 grid grid-cols-2 max-md:text-sm gap-x-2 gap-y-3"></ul>
            </div>
        </div>
        <div class="flex justify-start mt-4 mx-4 max-md:mx-0">
            <button id="submitBtn"
                class="bg-secondary hover:bg-primary text-primary hover:text-secondary px-4 py-3 rounded-md uppercase tracking-widest duration-200"
                type="button">
                Submit
            </button>
        </div>
    </main>
</x-erb-layout>
<script>

</script>
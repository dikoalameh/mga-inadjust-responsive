@section('title', 'Approved Accounts')
<x-erb-layout>
    <!-- Main Content -->
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-md:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            APPROVED ACCOUNTS
        </h2>
        <br>

        <table id="myTable" class="display overflow-scroll border-collapse w-full">
            <thead class="bg-primary text-white text-lg/7 max-lg:text-base/7">
                <tr class="header-table">
                    <th class="w-[33.33%]">P.I. Name</th>
                    <th class="w-[33.33%]">Date Registered</th>
                    <th class="w-[33.33%]">Date Approved</th>
                </tr>
            </thead>
            <tbody class="text-base/7 max-lg:text-sm/6">
                <tr>
                    <td>Renate Messner</td>
                    <td>4/15/2025<br>21:37:23</td>
                    <td>Pending</td>
                </tr>
                <tr>
                    <td>John Doe</td>
                    <td>4/15/2025<br>21:37:23</td>
                    <td>4/16/2025<br>22:57:53</td>
                </tr>
                <tr>
                    <td>John Steel</td>
                    <td>4/15/2025<br>21:37:23</td>
                    <td>Pending</td>
                </tr>
                <tr>
                    <td>Carlos González</td>
                    <td>4/15/2025<br>21:37:23</td>
                    <td>4/15/2025<br>21:37:23</td>
                </tr>
            </tbody>
        </table>
    </main>
</x-erb-layout>
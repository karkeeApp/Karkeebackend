@($menu)

<div class="table-responsive">
    <table class="table full-width">
        <tr>
            <th width="7%"></th>
            <th>Name</th>
            <th>Plate Number</th>
            <th>Registered</th>
        </tr>
        @if($members){
            @foreach($members as $key => $member){
                <tr>
                    <td>@($key+1).</td>
                    <td>@($member->name)</td>
                    <td>@($member->plate_no)</td>
                    <td>@($member->user ? 'Yes' : 'No')</td>
                </tr>
            }
        }
    </table>
</div>

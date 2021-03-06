<?php
session_start();
$pagename = "list of pesonal message";
require("includes/config.php");
require("includes/header.php");
require ("includes/inner-top.php");
?>
<html>
    <body>
        <div class="content">
<?php
//We check if the user is logged
if(isset($_SESSION['USERNAME']))
{
//We list his messages in a table
//Two queries are executes, one for the unread messages and another for read messages
$req1 = mysqli_query($dbc,'select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.username from pm as m1, pm as m2,users where ((m1.user1="'.$_SESSION['USERID'].'" and m1.user1read="no" and users.id=m1.user2) or (m1.user2="'.$_SESSION['USERID'].'" and m1.user2read="no" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc');
$req2 = mysqli_query($dbc,'select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.username from pm as m1, pm as m2,users where ((m1.user1="'.$_SESSION['USERID'].'" and m1.user1read="yes" and users.id=m1.user2) or (m1.user2="'.$_SESSION['USERID'].'" and m1.user2read="yes" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc');
?>
This is the list of your messages:<br />
<a href="new_pm.php" class="link_new_pm">New PM</a><br />
<h3>Unread Messages(<?php echo intval(mysqli_num_rows($req1)); ?>):</h3>
<table>
	<tr>
    	<th class="title_cell">Title</th>
        <th># of Replies</th>
        <th>Participant</th>
        <th>Date of creation</th>
    </tr>
<?php
//We display the list of unread messages
while($dn1 = mysqli_fetch_array($req1))
{
?>
	<tr>
    	<td class="left"><a href="read_pm.php?id=<?php echo $dn1['id']; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $dn1['reps']-1; ?></td>
    	<td><?php echo htmlentities($dn1['username'], ENT_QUOTES, 'UTF-8'); ?></td>
    	<td><?php echo "<small>".date('Y/m/d H:i:s' ,$dn1['timestamp'])."</small>" ; ?></td>
    </tr>
<?php
}
//If there is no unread message we notice it
if(intval(mysqli_num_rows($req1))==0)
{
?>
	<tr>
            <td colspan="4"><small>You have no unread message.</small></td>
    </tr>
<?php
}
?>
</table>
<br />
<h3>Read Messages(<?php echo intval(mysqli_num_rows($req2));//intval gets the integet value ex: 4.5 will be taken as4 ?>):</h3>
<table>
	<tr>
    	<th class="title_cell">Title</th>
        <th># of Replies</th>
        <th>Participant</th>
        <th>Date or creation</th>
    </tr>
<?php
//We display the list of read messages
while($dn2 = mysqli_fetch_array($req2))
{
?>
    
	<tr>
    	<td class="left"><a href="read_pm.php?id=<?php echo $dn2['id']; ?>"><?php echo htmlentities($dn2['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $dn2['reps']-1; ?></td>
    	<td><?php echo htmlentities($dn2['username'], ENT_QUOTES, 'UTF-8'); ?></td>
    	<td><?php echo "<small>"  .date('Y/m/d H:i:s' ,$dn2['timestamp'])  ." </small>"; ?></td>
    </tr>
<?php
}
//If there is no read message we notice it
if(intval(mysqli_num_rows($req2))==0)
{
?>
	<tr>
    	<td colspan="4" class="center">You have no read message.</td>
    </tr>
<?php
}
?>
</table>
<?php
}
else
{
	echo 'You must be logged to access this page.';
}
?>
		</div>
	</body>
</html>

<?php
require ("includes/inner-bottom.php");
require("includes/footer.php");
?>
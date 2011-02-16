
function rootsConfirm(winText,trueAction)
{
  var r=confirm(winText);
  if (r==true) {
   window.location = trueAction;
  } else {
    window.location = "#";
  }
}
#!/usr/bin/perl
$|=1;
while(<>){
  open(PIPE, "|/usr/bin/curl --data-urlencode source\@- " . $ENV{'TEST_URL'}) || next;
  print PIPE $_;
  close(PIPE);
}

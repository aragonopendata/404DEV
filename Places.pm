package Places;

use Utils;

sub search
{
	my $word = shift;
	my $location = shift;
	my $distance = shift;
	my $location = Utils::getLoc($location);
	my $searchResult = Utils::getPlaces($word,$location->{lat},$location->{lng},$distance);
	
	my $total = ();
	foreach(@$searchResult){	
		foreach(@{$_->{results}}){
			my $result = Utils::getPlaceInfo($_->{reference}); 				
			parseResult($result);
			#push(@$total,parse_result($result));
		}
	}
	return $total; 
}


sub parseResult
{
	print Dumper(shift);
}

1;

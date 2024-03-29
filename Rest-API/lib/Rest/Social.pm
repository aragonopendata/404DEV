package Rest::Social;

use strict;
use warnings;

use WWW::Mechanize;
use Data::Dumper;
use JSON::XS;

sub new {
	my ( $class, $params ) = @_;
    my $objectRef = {
        END_POINT => 'http://opendata.aragon.es/socialdata/data?locality=name&distance=distancia&source='.$params->{type}.'&page=',
        END_POINT_CORDS => 'http://opendata.aragon.es/socialdata/data?center=lat,lng&distance=distancia&source='.$params->{type}.'&page=',
        TYPE => $params->{type}
    };
    bless $objectRef, $class;
    return $objectRef;
}

sub init {
	my $self = shift;
	$self->{MECHANIZE} = WWW::Mechanize->new(autocheck => 0);
	return $self;
}

sub process {
	my ($self, $coordinates , $distance , $maxPage ,$name ) = @_;

	my $page = 0;
	my $total = ();
	while($page < $maxPage){
		$self->_prepareQUery($coordinates, $distance, $page , $name);
		my $response = $self->{MECHANIZE}->get($self->{QUERY});
		my $jsonString = $response->content;
		my $json = {}; 
		if($self->{TYPE} ne 'twitter'){
			eval { $json = JSON::XS->new->utf8->decode($jsonString)};
		}else{
			eval { $json = JSON::XS->new->decode($jsonString)};
		}
		if($@){
			# Error block
			print "$@";
		}else{
			$json = $json->{results} if ref $json ne 'ARRAY';	
			foreach my $result (@{$json}){
				push(@$total,$self->_parseResult($result));
			}
		}
		$page ++;
	}

	return $total;
}

sub _prepareQUery {
	my ($self, $coordinates, $distance , $page, $name) = @_;
	if (!$name){
		$self->{END_POINT_CORDS} =~ s/lat/$coordinates->{lat}/;
		$self->{END_POINT_CORDS} =~ s/lng/$coordinates->{lng}/;
		$self->{END_POINT_CORDS} =~ s/distancia/$distance/;
		$self->{QUERY} = $self->{END_POINT_CORDS};
	}else{
		$self->{END_POINT} =~ s/name/$name/;
		$self->{END_POINT} =~ s/distancia/$distance/;
		$self->{QUERY} = $self->{END_POINT};
	}
	$self->{QUERY} =~ s/&page=.*/&page=$page/i;
}

sub _parseResult {
	my ($self, $result) = @_;
	
	my $description = $result->{description};
	# Avoid errors in json encode
	$result->{author} =~ s/"//g if $result->{author};
	$result->{description} =~ s/\"//g if $result->{description};
	$result->{description} =~ s/\\n//g if $result->{description};
	$result->{thumbnail} =~ s/"//g if $result->{thumbnail};
	$result->{url} =~ s/"//g if $result->{url};

	my $hashtags = [];
	if($description){
		while($description =~ /(#[a-zA-Z0-9-_]+)/){
			my $word = $1;
			$description =~ s/$word//;
			push(@$hashtags,$word);
		}
	}

	$result->{description} = '' if $self->{TYPE} eq 'flickr';

	return {author => $result->{author}, lat => $result->{lat}, lng => $result->{lng}, description => $result->{description}, url => $result->{url}, thumbnail => $result->{thumbnail}, published_on => $result->{published_on} , hashtags => $hashtags};
}

1;